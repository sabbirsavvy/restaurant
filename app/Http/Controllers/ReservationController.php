<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\OpeningHour;

class ReservationController extends Controller
{
    // Display the "Book Table" form
    public function create()
    {
        return view('reservations.create');
    }

    // Handle reservation form submission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'number_of_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        // Check opening hours for the given day
        $day = strtolower(Carbon::parse($validated['date'])->format('D'));
        $openingHour = OpeningHour::where('day', $day)->first();

        if ($openingHour) {
            $reservationTime = Carbon::parse($validated['date'] . ' ' . $validated['time']);
            $open = Carbon::parse($validated['date'] . ' ' . $openingHour->open_time);
            $close = Carbon::parse($validated['date'] . ' ' . $openingHour->close_time);

            // If closing time is past midnight
            if ($close->lt($open)) {
                $close->addDay();
            }

            if (!$reservationTime->between($open, $close)) {
                return redirect()->back()->withErrors([
                    'time' => "⏳ We are currently closed.\n\nPlease reserve during our opening hours:\n- Mon to Fri: 12:00 PM – 11:00 PM\n- Saturday: 12:00 PM – 12:00 AM\n- Sunday: 12:00 PM – 10:00 PM\n\n📞 For urgent bookings, call us during opening hours at 01234 567890."
                ])->withInput();
                
            }
        }

        // Save reservation
        $reservation = new Reservation();
        $reservation->name = $validated['name'];
        $reservation->date = $validated['date'];
        $reservation->time = $validated['time'];
        $reservation->number_of_guests = $validated['number_of_guests'];
        $reservation->special_requests = $validated['special_requests'] ?? null;
        $reservation->status = 'pending';

        if (Auth::check()) {
            $reservation->user_id = Auth::id();
        }

        $reservation->save();
        session(['reservation_id' => $reservation->id]);

        return view('reservations.waiting');
    }

    // Return reservation status via AJAX
    public function status(Reservation $reservation)
    {
        return response()->json([
            'status' => $reservation->status,
            'date' => $reservation->date,
            'time' => $reservation->time,
        ]);
    }

    // Allow user to cancel pending reservation
    public function cancel(Request $request, Reservation $reservation)
    {
        if ($reservation->status === 'pending') {
            $reservation->status = 'cancelled';
            $reservation->save();
        }

        return response()->json(['status' => 'cancelled']);
    }

    // Show user reservations
    public function userReservations()
    {
        $reservations = auth()->user()->reservations()->orderByDesc('created_at')->get();
        return view('users.reservations', compact('reservations'));
    }
}
