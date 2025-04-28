<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);
        $details = Session::get('checkout_details');

        if (empty($cart) || empty($details)) {
            return redirect()->route('order.index')->with('error', 'Checkout session expired or cart is empty.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($cart as $id => $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'GBP',
                    'unit_amount' => $item['price'] * 100,
                    'product_data' => [
                        'name' => $item['title'],
                    ],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $metadata = array_merge($details, [
            'cart' => json_encode($cart),
        ]);

        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'customer_email' => $details['email'],
            'metadata' => $metadata,
        ]);

        return redirect($checkoutSession->url);
    }

    public function success(Request $request)
{
    $sessionId = $request->get('session_id');
    if (!$sessionId) {
        return redirect()->route('order.index')->with('error', 'Invalid session.');
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));
    $session = \Stripe\Checkout\Session::retrieve($sessionId);

    $metadata = $session->metadata->toArray();
    $cart = json_decode($metadata['cart'] ?? '[]', true);

    $total = collect($cart)->reduce(function ($carry, $item) {
        return $carry + ($item['price'] * $item['quantity']);
    }, 0);

    // ✅ Default full datetime schedule fallback
    $schedule = $metadata['schedule'] ?? now()->addMinutes(30)->toDateTimeString();

    // ✅ Check if restaurant is currently open
    $openingHours = (new \App\Http\Controllers\OrderController)->getOpeningHours();
    $todayKey = strtolower(now()->format('D'));
    $openTime = \Carbon\Carbon::parse($openingHours[$todayKey]['open']);
    $closeTime = \Carbon\Carbon::parse($openingHours[$todayKey]['close'])->subHour();
    $now = now();

    $isOpen = $now->between($openTime, $closeTime);
    $isPreorder = !$isOpen;

    // ✅ Create Order
    $order = Order::create([
        'user_id'         => Auth::id(),
        'name'            => $metadata['name'] ?? null,
        'email'           => $metadata['email'] ?? null,
        'phone'           => $metadata['phone'] ?? null,
        'address1'        => $metadata['address1'] ?? null,
        'address2'        => $metadata['address2'] ?? null,
        'city'            => $metadata['city'] ?? null,
        'county'          => $metadata['county'] ?? null,
        'postcode'        => $metadata['postcode'] ?? null,
        'type'            => $metadata['type'] ?? null,
        'payment_method'  => 'card',
        'schedule'        => $schedule,
        'order_details'   => json_encode($cart),
        'total_amount'    => $total,
        'status'          => $isPreorder ? 'accepted' : 'pending',
    ]);

    // ✅ Clear session
    Session::forget('cart');
    Session::forget('checkout_details');

    // ✅ Redirect depending on restaurant open status
    if ($isPreorder) {
        return redirect()->route('order.success', ['order' => $order->id, 'preorder' => 'yes']);
    }

    return redirect()->route('order.processing', ['order' => $order->id]);
}



    public function cancel(Request $request)
    {
        return view('checkout.cancel');
    }
}
