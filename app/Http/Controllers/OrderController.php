<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MenuItem;
use App\Models\Order;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::all();
        return view('order.index', compact('menuItems'));
        
    }

    function restaurantSettings()
{
    return cache('restaurant_settings', [
        'accepting_orders' => true,
        'accepting_preorders' => true,
        'accepting_reservations' => true,
        'collection_time' => 30,
        'delivery_time' => 60,
    ]);
}

    public function addToCart(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $quantity = $request->input('quantity', 1);
        $item = MenuItem::findOrFail($id);
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'title' => $item->title,
                'price' => $item->price,
                'quantity' => $quantity,
            ];
        }

        Session::put('cart', $cart);
        session(['openCategory' => $item->category]);

        $message = $quantity > 1
            ? "$quantity × {$item->title} have been added to your bag!"
            : "{$item->title} has been added to your bag!";
        
        return redirect()->back()->with('success', $message);
    }

    public function showCart()
    {
        $cart = Session::get('cart', []);
        return view('order.cart', compact('cart'));
    }

    public function removeFromCart(Request $request, $id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function clearCart()
    {
        Session::forget('cart');
        return redirect()->back()->with('success', 'Cart cleared!');
    }

    public function details(Request $request)
    {   
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to continue to checkout.');
        }

        $user = auth()->user();

        $openingHours = $this->getOpeningHours();
        $todayKey = strtolower(now()->format('D'));
        $openTime = Carbon::parse($openingHours[$todayKey]['open']);
        $closeTime = Carbon::parse($openingHours[$todayKey]['close'])->subHour();
        $now = now();

        $isOpen = $now->between($openTime, $closeTime);

        $collectionEstimate = $isOpen ? $now->copy()->addMinutes(30) : $openTime->copy()->addMinutes(30);
        $deliveryEstimate   = $isOpen ? $now->copy()->addHour() : $openTime->copy()->addHour();

        return view('checkout.details', compact(
            'user', 'isOpen', 'collectionEstimate', 'deliveryEstimate', 'openingHours'
        ));
    }
    

    public function process(Request $request)
{
    $cart = Session::get('cart', []);
    if (empty($cart)) {
        return redirect()->route('order.index')->with('error', 'Your cart is empty.');
    }

    $request->validate([
        'name'            => 'required|string|max:255',
        'email'           => 'required|email|max:255',
        'phone'           => 'required|string|max:20',
        'type'            => 'required|in:collection,delivery',
        'schedule'        => 'nullable|date_format:H:i',
        'payment_method'  => 'required|in:cash,card',
        'address1'        => 'required_if:type,delivery',
        'city'            => 'required_if:type,delivery',
        'county'          => 'required_if:type,delivery',
        'postcode'        => 'required_if:type,delivery',
    ]);

    $userId = auth()->check() ? auth()->id() : null;
    $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

    $openingHours = $this->getOpeningHours();
    $now = now();
    $dayKey = strtolower($now->format('D'));
    $open = Carbon::parse($openingHours[$dayKey]['open']);
    $close = Carbon::parse($openingHours[$dayKey]['close'])->subHour();
    $isOpen = $now->between($open, $close);

    $collectionTime = $isOpen ? $now->copy()->addMinutes(30) : $open->copy()->addMinutes(30);
    $deliveryTime   = $isOpen ? $now->copy()->addHour() : $open->copy()->addHour();
    $isPreorder     = !$isOpen;
    $defaultTime    = $request->type === 'collection' ? $collectionTime : $deliveryTime;

    $scheduleTime = $request->schedule
        ? Carbon::today()->setTimeFromTimeString($request->schedule)
        : $defaultTime;

    // 🔁 Handle Cash Orders
    if ($request->payment_method === 'cash') {
        $order = Order::create([
            'user_id'        => $userId,
            'order_details'  => json_encode($cart),
            'total_amount'   => $total,
            'status'         => $isPreorder ? 'accepted' : 'pending',
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'address1'       => $request->address1 ?? null,
            'address2'       => $request->address2 ?? null,
            'city'           => $request->city ?? null,
            'county'         => $request->county ?? null,
            'postcode'       => $request->postcode ?? null,
            'type'           => $request->type,
            'payment_method' => 'cash',
            'schedule'       => $scheduleTime,
        ]);

        Session::forget('cart');

        // ✅ Route based on open status
        if ($isPreorder) {
            return redirect()->route('order.success', [
                'order'    => $order->id,
                'preorder' => 'yes',
            ]);
        } else {
            return redirect()->route('order.processing', ['order' => $order->id]);
        }
    }

    // 🔁 Handle Stripe Orders
    Session::put('checkout_details', [
        'user_id'        => $userId,
        'cart'           => $cart,
        'total'          => $total,
        'name'           => $request->name,
        'email'          => $request->email,
        'phone'          => $request->phone,
        'address1'       => $request->address1,
        'address2'       => $request->address2,
        'city'           => $request->city,
        'county'         => $request->county,
        'postcode'       => $request->postcode,
        'type'           => $request->type,
        'schedule'       => $scheduleTime->toDateTimeString(),
        'preorder'       => $isPreorder,
    ]);

    return redirect()->route('checkout.process.stripe');
}

public function processing($orderId)
{
    $order = Order::findOrFail($orderId);

    $openingHours = $this->getOpeningHours();
    $todayKey = strtolower(now()->format('D'));
    $openTime = Carbon::parse($openingHours[$todayKey]['open']);
    $closeTime = Carbon::parse($openingHours[$todayKey]['close'])->subHour();
    $now = now();

    $isOpen = $now->between($openTime, $closeTime);
    $nextOpen = $openTime;

    $collectionTime = $isOpen ? $now->copy()->addMinutes(30) : $openTime->copy()->addMinutes(30);
    $deliveryTime   = $isOpen ? $now->copy()->addHour() : $openTime->copy()->addHour();
    $preorder       = !$isOpen;

    $estimateTime = Carbon::parse($order->schedule)->format('l, jS M Y • g:i A');
    $openFormatted = $this->getOpeningTime();

    return view('order.processing', [
        'order'          => $order,
        'preorder'       => $preorder,
        'estimateTime'   => $estimateTime,
        'openTime'       => $openFormatted,
        'isOpen'         => $isOpen,
        'nextOpen'       => $nextOpen,
        'collectionTime' => $collectionTime,
        'deliveryTime'   => $deliveryTime,
    ]);
}



    public function success(Request $request)
    {
        $order = Order::findOrFail($request->order);
        $openingHours = $this->getOpeningHours();
        $todayKey = strtolower(now()->format('D'));

        $openTime = Carbon::parse($openingHours[$todayKey]['open']);
        $closeTime = Carbon::parse($openingHours[$todayKey]['close'])->subHour();
        $now = now();

        $isOpen = $now->between($openTime, $closeTime);
        $nextOpen = $openTime;

        $collectionTime = $isOpen ? $now->copy()->addMinutes(30) : $openTime->copy()->addMinutes(30);
        $deliveryTime   = $isOpen ? $now->copy()->addHour() : $openTime->copy()->addHour();
        $preorder = !$isOpen;

        $estimateTime = Carbon::parse($order->schedule)->format('l, jS M Y • g:i A');
        $openFormatted = $this->getOpeningTime();

        return view('order.success', [
            'order'         => $order,
            'preorder'      => $preorder,
            'estimateTime'  => $estimateTime,
            'openTime'      => $openFormatted,
            'isOpen'        => $isOpen,
            'nextOpen'      => $nextOpen,
            'collectionTime'=> $collectionTime,
            'deliveryTime'  => $deliveryTime,
        ]);
    }


    public function status($orderId)
    {
        $order = Order::findOrFail($orderId);
        return response()->json(['status' => $order->status]);
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, (int) $request->quantity);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function getOpeningHours()
    {
        return [
            'mon' => ['open' => '12:00', 'close' => '23:00'],
            'tue' => ['open' => '12:00', 'close' => '23:00'],
            'wed' => ['open' => '12:00', 'close' => '23:00'],
            'thu' => ['open' => '12:00', 'close' => '23:00'],
            'fri' => ['open' => '12:00', 'close' => '23:00'],
            'sat' => ['open' => '12:00', 'close' => '23:59'],
            'sun' => ['open' => '12:00', 'close' => '22:00'],
        ];
    }

    private function getOpeningTime()
    {
        $openingHours = $this->getOpeningHours();
        $key = strtolower(now()->format('D'));
        return Carbon::parse($openingHours[$key]['open'])->format('g:i A');
    }

    public function userOrders()
{
    $orders = Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
    return view('users.orders', compact('orders'));
}

public function viewUserOrder($id)
{
    $order = Order::findOrFail($id);
    return view('users.viewdetails', compact('order'));
}

public function downloadInvoice($id)
{
    $order = Order::findOrFail($id);
    $pdf = Pdf::loadView('users.invoice', compact('order'));
    return $pdf->download('order_invoice_'.$order->id.'.pdf');
}

public function userViewDetails($orderId)
{
    $order = Order::findOrFail($orderId);
    return view('users.order-details', compact('order'));
}

}
