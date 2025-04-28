<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Orders - Admin</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 30% 30%, rgba(138, 180, 248, 0.05), transparent 50%),
                  radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.04), transparent 50%);
      z-index: -1;
    }

    .collapsed {
      transform: translateX(-100%);
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      background: #121212;
      padding: 70px 20px 20px;
      transition: transform 0.3s ease-in-out;
      z-index: 1000;
    }

    .sidebar h2 {
      color: #8ab4f8;
      font-family: 'Orbitron', sans-serif;
      font-size: 1.5rem;
      margin-bottom: 2rem;
      text-align: center;
    }

    .sidebar a {
      display: block;
      padding: 12px;
      margin: 6px 0;
      color: #8ab4f8;
      font-weight: 600;
      border-radius: 6px;
      text-decoration: none;
      transition: 0.3s;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #8ab4f8;
      color: #000;
    }

    .logout-btn {
      background-color: #e74c3c;
      color: white;
      padding: 12px;
      width: 100%;
      margin-top: 20px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    .logout-btn:hover {
      background-color: #c0392b;
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
      cursor: pointer;
      z-index: 1100;
    }

    .main-content {
      margin-left: 250px;
      padding: 3rem 2rem;
      transition: margin-left 0.3s ease-in-out;
    }

    .content-collapsed {
      margin-left: 0;
    }

    h1 {
      text-align: center;
      font-family: 'Orbitron', sans-serif;
      font-size: 2rem;
      color: #8ab4f8;
      margin-bottom: 2rem;
    }

    .orders-table {
      width: 100%;
      border-collapse: collapse;
      background: #1e1e1e;
      border-radius: 8px;
      overflow: hidden;
    }

    .orders-table th, .orders-table td {
      padding: 14px;
      border-bottom: 1px solid #2f2f2f;
      text-align: left;
    }

    .orders-table th {
      background: #2a2a2a;
      color: #8ab4f8;
      text-transform: uppercase;
      font-weight: 600;
    }

    .orders-table tbody tr:hover {
      background-color: rgba(138, 180, 248, 0.05);
    }

    .status-pending {
      color: #f39c12;
      font-weight: bold;
    }

    .status-accepted {
      color: #2ecc71;
      font-weight: bold;
    }

    .action-btn {
      padding: 8px 14px;
      font-weight: bold;
      border-radius: 6px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      transition: 0.3s;
    }

    .accept-btn {
      background-color: #2ecc71;
      color: white;
    }

    .accept-btn:hover {
      background-color: #27ae60;
    }

    .empty-message {
      text-align: center;
      color: #bbb;
      font-size: 1.2rem;
      margin-top: 20px;
    }
  </style>
</head>
<body x-data="{ sidebarOpen: true }">

  <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen">☰ Menu</button>

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

  <main class="main-content" :class="{'content-collapsed': !sidebarOpen}">
    <h1>Orders</h1>

    @if(session('success'))
      <div class="text-green-500 text-center font-semibold mb-4">
        {{ session('success') }}
      </div>
    @endif

    @if($orders->count())
    <table class="orders-table">
    <thead>
  <tr>
    <th>Order ID</th>
    <th>User</th>
    <th>Total</th>
    <th>Type</th>
    <th>Payment</th>
    <th>Status</th>
    <th>Estimated Time</th>
    <th>Action</th>
  </tr>
</thead>

  <tbody>
    @foreach($orders as $order)
      <tr>
        <td>{{ $order->id }}</td>
        <td>{{ optional($order->user)->name }}</td>
        <td>£{{ number_format($order->total_amount, 2) }}</td>
        <td>{{ ucfirst($order->type) }}</td>

<td>{{ ucfirst($order->payment_method) }}</td>
        <td class="{{ $order->status == 'pending' ? 'status-pending' : 'status-accepted' }}">
          {{ ucfirst($order->status) }}
        </td>
        <td>
          @if($order->schedule)
            {{ \Carbon\Carbon::parse($order->schedule)->format('D, j M • g:i A') }}
          @else
            <span class="text-gray-400">Not scheduled</span>
          @endif
        </td>
        <td>
          <a href="{{ route('admin.orders.accept', $order->id) }}" class="action-btn accept-btn">
            Accept
          </a>
          <a href="{{ route('admin.orders.decline', $order->id) }}" class="action-btn" style="background-color: #e74c3c; color: white;">
            Reject
          </a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

    @else
      <p class="empty-message">No pending orders.</p>
    @endif
  </main>

</body>
</html>
