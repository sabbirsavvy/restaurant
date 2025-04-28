<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Reservations – Dune Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      background-color: #0a0a0a;
      color: #d1d1d1;
      font-family: 'Roboto Mono', monospace;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(ellipse at center, rgba(58, 123, 213, 0.1), transparent 70%),
                  radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.03), transparent 40%);
      z-index: -1;
    }

    .nav-link {
      color: #8ab4f8;
      transition: 0.3s;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .nav-link:hover { color: #ffffff; }

    .text-gold {
      color: #c4a000;
      font-family: 'Orbitron', sans-serif;
      letter-spacing: 1.5px;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 2rem 1rem;
    }

    h1, h2 {
      font-family: 'Orbitron', sans-serif;
      color: #8ab4f8;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: rgba(34, 34, 34, 0.95);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 15px rgba(138, 180, 248, 0.1);
      margin-bottom: 3rem;
    }

    th, td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #1e3a8a;
    }

    th {
      background-color: #1c1c1c;
      color: #8ab4f8;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    tr:hover {
      background-color: rgba(138, 180, 248, 0.05);
    }

    .no-reservations {
      text-align: center;
      color: #999;
      font-style: italic;
      margin-bottom: 3rem;
    }

    .status-accepted {
      color: #00ff88;
      font-weight: bold;
    }

    .status-declined {
      color: #ff4d4d;
      font-weight: bold;
    }

    footer {
      background: #0c0c0c;
      color: #666;
      padding: 1rem;
      text-align: center;
      margin-top: auto;
    }

    footer a {
      color: #8ab4f8;
      margin: 0 10px;
      text-decoration: none;
    }

    footer a:hover {
      color: white;
    }
  </style>
</head>
<body>

<!-- ✅ Navbar -->
<header class="bg-black w-full fixed top-0 left-0 z-50 shadow-md">
  <div class="container mx-auto px-6 py-4 flex items-center justify-between">
    <div class="text-3xl font-bold text-gold tracking-wide">
      <a href="{{ url('/') }}" class="nav-link">Dune Restaurant</a>
    </div>
    <nav>
      <ul class="flex space-x-6">
        <li><a href="{{ url('/') }}" class="nav-link">Home</a></li>
        <li><a href="{{ url('/menu') }}" class="nav-link">Menu</a></li>
        <li><a href="{{ url('/order-online') }}" class="nav-link">Order Online</a></li>
        <li><a href="{{ url('/book-table') }}" class="nav-link">Reserve Table</a></li>
        <li><a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a></li>
      </ul>
    </nav>
  </div>
</header>

<main class="mt-24 container">
  <h1>📅 Your Reservations</h1>

  <!-- ✅ Upcoming Accepted Reservations -->
   <br>
   <br>
<h2>🟢 Upcoming Reservations</h2>
@php
  use Carbon\Carbon;
  $now = Carbon::now();
  $upcoming = $reservations->filter(function ($r) use ($now) {
      return $r->status === 'accepted' &&
             Carbon::parse($r->date . ' ' . $r->time)->greaterThan($now);
  })->sortBy(function ($r) {
      return Carbon::parse($r->date . ' ' . $r->time);
  });
@endphp
@if($upcoming->count())
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Guests</th>
        <th>Status</th>
        <th>Booked At</th>
      </tr>
    </thead>
    <tbody>
      @foreach($upcoming as $reservation)
        <tr>
          <td>{{ $reservation->id }}</td>
          <td>{{ $reservation->name }}</td>
          <td>{{ \Carbon\Carbon::parse($reservation->date)->format('d-m-Y') }}</td>
          <td>{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
          <td>{{ $reservation->number_of_guests }}</td>
          <td class="status-accepted">{{ ucfirst($reservation->status) }}</td>
          <td>{{ $reservation->created_at->format('d-m-Y H:i') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <p class="no-reservations">No upcoming reservations yet.</p>
@endif


  <!-- ✅ All Reservations -->
  <h2>📁 All Reservations</h2>
  @if($reservations->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Date</th>
          <th>Time</th>
          <th>Guests</th>
          <th>Status</th>
          <th>Booked At</th>
        </tr>
      </thead>
      <tbody>
      @foreach($reservations->sortByDesc('created_at') as $reservation)
      <tr>
            <td>{{ $reservation->id }}</td>
            <td>{{ $reservation->name }}</td>
            <td>{{ $reservation->date }}</td>
            <td>{{ $reservation->time }}</td>
            <td>{{ $reservation->number_of_guests }}</td>
            <td class="{{ $reservation->status === 'accepted' ? 'status-accepted' : ($reservation->status === 'declined' ? 'status-declined' : '') }}">
              {{ ucfirst($reservation->status) }}
            </td>
            <td>{{ $reservation->created_at->format('Y-m-d H:i') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p class="no-reservations">You haven't made any reservations yet.</p>
  @endif
</main>

<!-- ✅ Footer -->
<footer>
  <p>&copy; {{ date('Y') }} Restaurant. Exploring galaxies of taste.</p>
  <div class="mt-2">
    <a href="#">Facebook</a>
    <a href="#">Instagram</a>
    <a href="#">Twitter</a>
  </div>
</footer>

</body>
</html>
