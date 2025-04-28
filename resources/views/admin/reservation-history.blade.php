<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reservation History - Admin</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto+Mono&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      font-family: 'Roboto Mono', monospace;
      background-color: #0a0a0a;
      color: #d1d1d1;
      margin: 0;
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

    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      background: #121212;
      padding: 70px 20px 20px;
      transition: transform 0.3s ease-in-out;
      z-index: 1000;
      overflow-y: auto;
    }

    .sidebar h2 {
      color: #8ab4f8;
      font-family: 'Orbitron', sans-serif;
      font-size: 1.4rem;
      margin-bottom: 2rem;
      text-align: center;
    }

    .sidebar a {
      display: block;
      padding: 12px;
      margin: 6px 0;
      color: #8ab4f8;
      text-decoration: none;
      font-weight: 600;
      border-radius: 6px;
      transition: 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #8ab4f8;
      color: #000;
    }

    .logout-btn {
      background-color: #e74c3c;
      color: white;
      padding: 12px;
      margin-top: 20px;
      width: 100%;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    .logout-btn:hover {
      background-color: #c0392b;
    }

    .collapsed {
      transform: translateX(-100%);
    }

    .toggle-btn {
      position: fixed;
      top: 20px;
      left: 20px;
      background: #8ab4f8;
      color: #000;
      padding: 10px 14px;
      border-radius: 6px;
      font-weight: bold;
      z-index: 1100;
      cursor: pointer;
      transition: 0.3s;
    }

    .toggle-btn:hover {
      background: #ffffff;
    }

    .main-content {
      margin-left: 250px;
      padding: 2rem;
      transition: margin-left 0.3s ease-in-out;
      flex: 1;
    }

    .content-collapsed {
      margin-left: 0;
    }

    .page-title {
      font-family: 'Orbitron', sans-serif;
      text-align: center;
      color: #8ab4f8;
      font-size: 2rem;
      margin-bottom: 2rem;
    }

    .history-table {
      width: 100%;
      border-collapse: collapse;
      background: #1e1e1e;
      border-radius: 8px;
      overflow: hidden;
    }

    .history-table th, .history-table td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #2f2f2f;
    }

    .history-table th {
      background-color: #2a2a2a;
      color: #8ab4f8;
      font-weight: 600;
      text-transform: uppercase;
    }

    .history-table tbody tr:hover {
      background-color: rgba(58, 123, 213, 0.08);
    }

    .status-pending {
      color: #f39c12;
      font-weight: bold;
    }

    .status-accepted {
      color: #2ecc71;
      font-weight: bold;
    }

    .status-declined {
      color: #e74c3c;
      font-weight: bold;
    }

    footer {
      background-color: #121212;
      color: #888;
      padding: 1rem;
      text-align: center;
      font-size: 0.9rem;
    }

    footer a {
      color: #8ab4f8;
      text-decoration: none;
      margin: 0 10px;
    }

    footer a:hover {
      color: white;
    }
  </style>
</head>
<body x-data="{ sidebarOpen: true }">

  <!-- Sidebar Toggle Button -->
  <button @click="sidebarOpen = !sidebarOpen" class="toggle-btn">☰ Menu</button>

  <!-- Sidebar -->
  <aside class="sidebar" :class="{'collapsed': !sidebarOpen}">
    <h2>Restaurant</h2>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('admin.menu') }}" class="{{ request()->is('admin/menu') ? 'active' : '' }}">Manage Menu</a>
    <a href="{{ route('admin.orders') }}" class="{{ request()->is('admin/orders') ? 'active' : '' }}">Pending Orders</a>
    <a href="{{ route('admin.reservations') }}" class="{{ request()->is('admin/reservations') ? 'active' : '' }}">Pending Reservation</a>
    <a href="{{ route('admin.order.history') }}" class="{{ request()->is('admin/order/history') ? 'active' : '' }}">Order History</a>
    <a href="{{ route('admin.reservation.history') }}" class="{{ request()->is('admin/reservation/history') ? 'active' : '' }}">Reservation History</a>
    <a href="{{ url('/') }}" class="text-red-400 mt-4 block">← Go to Website</a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="logout-btn">Logout</button>
    </form>
  </aside>

  <!-- Main Content -->
  <main class="main-content" :class="{ 'content-collapsed': !sidebarOpen }">
    <h1 class="page-title">Reservation History</h1>

    @if($reservations->count())
      <table class="history-table">
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
          @foreach($reservations as $reservation)
            <tr>
              <td>{{ $reservation->id }}</td>
              <td>{{ $reservation->name }}</td>
              <td>{{ $reservation->date }}</td>
              <td>{{ $reservation->time }}</td>
              <td>{{ $reservation->number_of_guests }}</td>
              <td class="
                {{ $reservation->status == 'pending' ? 'status-pending' : '' }}
                {{ $reservation->status == 'accepted' ? 'status-accepted' : '' }}
                {{ $reservation->status == 'declined' ? 'status-declined' : '' }}
              ">{{ ucfirst($reservation->status) }}</td>
              <td>{{ $reservation->created_at->format('Y-m-d H:i') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p class="text-center text-gray-400">No reservations found.</p>
    @endif
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; {{ date('Y') }} Restaurant — Admin Panel. All rights reserved.</p>
  </footer>

</body>
</html>
