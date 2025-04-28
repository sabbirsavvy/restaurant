<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CheckRestaurantStatus
{
    public function handle($request, Closure $next)
    {
        $settings = Cache::get('restaurant_settings', []);

        // 🛑 Orders not allowed
        if ($request->is('order*') && !$settings['is_accepting_orders']) {
            return redirect()->route('home')->with('error', 'We are currently not taking any orders. Sorry for the inconvenience.');
        }

        // 🛑 Reservations not allowed
        if ($request->is('reservation*') && !$settings['accepting_reservations']) {
            return redirect()->route('home')->with('error', 'We are currently not taking any reservations. Sorry for the inconvenience.');
        }

        return $next($request);
    }
}

