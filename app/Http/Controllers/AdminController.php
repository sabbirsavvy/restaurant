<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\Order;
use App\Notifications\OrderStatusNotification;
use App\Notifications\ReservationStatusNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    // ✅ Admin Dashboard
    public function dashboard()
{
    $menuCount = MenuItem::count();
    $reservationCount = Reservation::count();
    $pendingReservationCount = Reservation::where('status', 'pending')->count();
    $orderCount = Order::count();
    $pendingOrderCount = Order::where('status', 'pending')->count();

    // Get orders per day for the last 7 days
    $ordersByDate = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    $chartLabels = $ordersByDate->pluck('date');
    $chartData = $ordersByDate->pluck('count');

    // Today's accepted reservations
    $todayReservations = Reservation::whereDate('date', now()->toDateString())
        ->where('status', 'accepted')
        ->orderBy('time', 'asc')
        ->get();

    // All pending reservations
    $pendingReservations = Reservation::where('status', 'pending')
        ->orderBy('date', 'asc')
        ->orderBy('time', 'asc')
        ->get();

    // 🔥 Add this line to fix your issue
    $pendingOrders = Order::where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.dashboard', compact(
        'menuCount',
        'reservationCount',
        'pendingReservationCount',
        'orderCount',
        'pendingOrderCount',
        'chartLabels',
        'chartData',
        'todayReservations',
        'pendingReservations',
        'pendingOrders' // 👈 include this
    ));
}


    // ✅ Manage Menu Page
    public function manageMenu()
{
    $menuItems = MenuItem::all();
    return view('admin.menu', compact('menuItems'));
}

    // ✅ Show Create Menu Form
    public function createMenuItem()
    {
        return view('admin.create-menu-item');
    }

    // ✅ Store New Menu Item
    public function storeMenuItem(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'is_featured' => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $menuItem = new MenuItem();
        $menuItem->title = $request->title;
        $menuItem->category = $request->category;
        $menuItem->description = $request->description;
        $menuItem->price = $request->price;
        $menuItem->is_featured = $request->is_featured ? true : false;

        if ($request->hasFile('image')) {
            $menuItem->image = $request->file('image')->store('menu_items', 'public');
        }

        $menuItem->save();

        return redirect()->back()->with('success', 'New menu item added successfully.');
    }

    // ✅ Show Edit Form
    public function editMenuItem($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        return view('admin.edit-menu-item', compact('menuItem'));
    }

    // ✅ Update Menu Item
    public function updateMenuItem(Request $request, $id)
    {
        $menuItem = MenuItem::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'is_featured' => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $menuItem->title = $request->title;
        $menuItem->category = $request->category;
        $menuItem->description = $request->description;
        $menuItem->price = $request->price;
        $menuItem->is_featured = $request->is_featured ? true : false;

        if ($request->hasFile('image')) {
            $menuItem->image = $request->file('image')->store('menu_items', 'public');
        }

        $menuItem->save();

        return redirect()->route('admin.menu')->with('success', 'Menu item updated successfully.');
    }

    // ✅ Delete Menu Item
    public function destroyMenuItem($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $menuItem->delete();
        return redirect()->route('admin.menu')->with('success', 'Menu item deleted successfully.');
    }

    // ✅ View Pending Reservations
    public function viewReservations()
    {
        $reservations = Reservation::where('status', 'pending')->get();
        return view('admin.reservations', compact('reservations'));
    }

    // ✅ Accept a Reservation & Notify User
    public function acceptReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'accepted';
        $reservation->save();

        if ($reservation->user) {
            $reservation->user->notify(new ReservationStatusNotification($reservation));
        }

        return redirect()->back()->with('success', 'Reservation accepted.');
    }

    // ✅ Decline a Reservation & Notify User
    public function declineReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'declined';
        $reservation->save();

        if ($reservation->user) {
            $reservation->user->notify(new ReservationStatusNotification($reservation));
        }

        return redirect()->back()->with('success', 'Reservation declined.');
    }

    // ✅ View Pending Orders
    public function viewOrders()
    {
        $orders = Order::where('status', 'pending')->with('user')->get();
        return view('admin.orders', compact('orders'));
    }

    public function viewOrderDetails($orderId)
{
    $order = Order::with('user')->findOrFail($orderId);
$user = $order->user;


    $orderItems = json_decode($order->order_details, true);

    return view('admin.order-details', compact('order', 'user', 'orderItems'));
}



    // ✅ Accept an Order & Notify User
    public function acceptOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'accepted';
        $order->save();

        if ($order->user) {
            $order->user->notify(new OrderStatusNotification($order));
        }

        return redirect()->back()->with('success', 'Order accepted.');
    }

    // ✅ View Order History
    public function orderHistory()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.order-history', compact('orders'));
    }

    // ✅ View Reservation History
    public function reservationHistory()
    {
        $reservations = Reservation::orderBy('created_at', 'desc')->get();
        return view('admin.reservation-history', compact('reservations'));
    }

    public function settings()
{
    $settings = cache()->get('restaurant_settings', [
        'is_open' => true,
        'preorders_enabled' => true,
        'accepting_reservations' => true,
        'collection_time' => 30,
        'delivery_time' => 60,
    ]);

    return view('admin.settings', compact('settings'));
}


public function declineOrder($id)
{
    $order = Order::findOrFail($id);
    $order->status = 'declined';
    $order->save();

    if ($order->user) {
        $order->user->notify(new OrderStatusNotification($order));
    }

    return redirect()->back()->with('success', 'Order declined.');
}

public function saveSettings(Request $request)
{
    $validated = $request->validate([
        'collection_time' => 'required|integer|min:0',
        'delivery_time' => 'required|integer|min:0',
    ]);

    $settings = [
        'is_open' => $request->has('is_open'),
        'preorders_enabled' => $request->has('preorders_enabled'),
        'accepting_reservations' => $request->has('accepting_reservations'),
        'collection_time' => $request->input('collection_time'),
        'delivery_time' => $request->input('delivery_time'),
    ];

    Cache::put('restaurant_settings', $settings);

    return redirect()->route('admin.settings')->with('success', 'Settings saved!');
}

public function updateSettings(Request $request)
{
    $settings = [
        'is_accepting_orders'      => $request->has('is_accepting_orders'),
        'allow_preorders'          => $request->has('allow_preorders'),
        'accepting_reservations'   => $request->has('accepting_reservations'),
        'collection_time'          => (int) $request->input('collection_time', 30),
        'delivery_time'            => (int) $request->input('delivery_time', 60),
    ];

    // Save to JSON or Cache
    Storage::disk('local')->put('restaurant-settings.json', json_encode($settings, JSON_PRETTY_PRINT));

    return back()->with('success', 'Settings saved successfully.');
}
}
