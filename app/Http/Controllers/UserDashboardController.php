<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Reservation;

class UserDashboardController extends Controller
{
    public function index()
    {
        // 1) Check if a user is logged in
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        // 2) Fetch orders for this user
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3) Fetch reservations for this user
        $reservations = Reservation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 4) Pass them to the "dashboard" view
        return view('dashboard', compact('orders', 'reservations'));
    }
}
