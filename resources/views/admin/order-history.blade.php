<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order History - Admin</title>
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

    .order-table {
      width: 100%;
      border-collapse: collapse;
      background: #1e1e1e;
      border-radius: 8px;
      overflow: hidden;
    }

    .order-table th, .order-table td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #2f2f2f;
    }

    .order-table th {
      background-color: #2a2a2a;
      color: #8ab4f8;
      font-weight: 600;
      text-transform: uppercase;
    }

    .order-table tbody tr:hover {
      background-color: rgba(58, 123, 213, 0.08);
    }

    .empty-message {
      text-align: center;
      color: #aaa;
      font-size: 1.2rem;
      margin-top: 2rem;
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

    .status-completed {
      color: #3498db;
      font-weight: bold;
    }

    .view-details-btn {
      background-color: #3498db;
      color: white;
      padding: 8px 12px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .view-details-btn:hover {
      background-color: #2980b9;
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

<!-- ...keep the head section the same... -->

<!-- Main Content -->
<main class="main-content" :class="{ 'content-collapsed': !sidebarOpen }">
  <h1 class="page-title">Order History</h1>

  @if($orders->count())
    <table class="order-table">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>User</th>
          <th>Total Amount</th>
          <th>Payment</th>
          <th>Type</th>
          <th>Status</th>
          <th>Placed At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
          <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name ?? 'Guest' }}</td>
            <td>£{{ number_format($order->total_amount, 2) }}</td>
            <td>{{ ucfirst($order->payment_method) }}</td>
            <td>{{ ucfirst($order->type) }}</td>
            <td class="
              {{ $order->status == 'pending' ? 'status-pending' : '' }}
              {{ $order->status == 'accepted' ? 'status-accepted' : '' }}
              {{ $order->status == 'declined' ? 'status-declined' : '' }}
              {{ $order->status == 'completed' ? 'status-completed' : '' }}
            ">{{ ucfirst($order->status) }}</td>
            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
            <td>
              <a href="{{ route('admin.order.details', $order->id) }}" class="view-details-btn">View Details</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p class="empty-message">No orders found.</p>
  @endif
</main>


  <!-- Footer -->
  <footer>
    <p>&copy; {{ date('Y') }} Restaurant — Admin Panel. All rights reserved.</p>
  </footer>

</body>
</html>
