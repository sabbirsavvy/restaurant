<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard - Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto+Mono&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      font-family: 'Roboto Mono', monospace;
      background-color: #0a0a0a;
      color: #d1d1d1;
      min-height: 100vh;
      margin: 0;
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
      background:rgb(255, 255, 255);
    }

    .main-content {
      margin-left: 250px;
      padding: 2rem;
      transition: margin-left 0.3s ease-in-out;
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

    .card {
      background-color: #1b1b1b;
      border-radius: 8px;
      padding: 24px;
      color: #d1d1d1;
      box-shadow: 0 0 20px rgba(138, 180, 248, 0.1);
      text-align: center;
      transition: 0.3s;
    }

    .card:hover {
      background-color: #2a2a2a;
      transform: scale(1.02);
    }

    .section-box {
      background-color: #1b1b1b;
      padding: 24px;
      border-radius: 8px;
      margin-bottom: 2rem;
      box-shadow: 0 0 20px rgba(138, 180, 248, 0.1);
    }

    .section-box h2 {
      font-family: 'Orbitron', sans-serif;
      color: #c4a000;
      margin-bottom: 1rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #2f2f2f;
    }

    th {
      background: #222;
      color: #8ab4f8;
      font-weight: 600;
      text-transform: uppercase;
    }

    tr:hover {
      background-color: rgba(58, 123, 213, 0.08);
    }
  </style>
</head>
<body x-data="{ sidebarOpen: true }">

  <!-- Sidebar Toggle -->
  <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen">☰ Menu</button>

  <!-- Sidebar -->
  <aside class="sidebar" :class="{'collapsed': !sidebarOpen}">
    <h2>Restaurant</h2>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('admin.settings') }}" class="{{ request()->is('admin/settings') ? 'active' : '' }}">Restaurant Settings</a>
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

  <!-- Main -->
  <main class="main-content" :class="{'content-collapsed': !sidebarOpen}">
    <h1 class="page-title">Restaurant - Admin Dashboard</h1>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <a href="{{ route('admin.menu') }}" class="card">
        <h3 class="text-lg font-semibold">Total Menu Items</h3>
        <p class="text-3xl text-blue-400">{{ $menuCount }}</p>
      </a>
      <a href="{{ route('admin.reservations') }}" class="card">
        <h3 class="text-lg font-semibold">Pending Reservations</h3>
        <p class="text-3xl text-red-400">{{ $pendingReservationCount }}</p>
      </a>
      
      <a href="{{ route('admin.orders') }}" class="card">
        <h3 class="text-lg font-semibold">Pending Orders</h3>
        <p class="text-3xl text-yellow-400">{{ $pendingOrderCount }}</p>
      </a>
      <a href="{{ route('admin.settings') }}" class="card hover:shadow-xl hover:shadow-purple-500/20 transition-all duration-300">
  <div class="flex items-center justify-between">
    <div>
      <h3 class="text-lg font-semibold text-purple-300">Restaurant Settings</h3>
      <p class="text-sm mt-1 text-gray-400"> Manage delivery, preorders</p>
    </div>
    <div class="text-purple-400 text-3xl">
      <i class="fas fa-cogs"></i>
    </div>
  </div>
</a>

    </div>

    <!-- Today's Reservations -->
    <div class="section-box">
      <h2>Today's Reservations</h2>
      @if($todayReservations->count())
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Time</th>
              <th>Guests</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($todayReservations as $reservation)
              <tr>
                <td>{{ $reservation->id }}</td>
                <td>{{ $reservation->name }}</td>
                <td>{{ $reservation->time }}</td>
                <td>{{ $reservation->number_of_guests }}</td>
                <td>{{ ucfirst($reservation->status) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <p>No reservations for today.</p>
      @endif
    </div>

    <!-- Orders Chart -->
    <div class="section-box">
      <h2>Orders in the Last 7 Days</h2>
      <canvas id="ordersChart" class="mt-4"></canvas>
    </div>
  </main>

  <!-- Chart.js Orders Chart -->
  <script>
    const ctx = document.getElementById('ordersChart').getContext('2d');
    const ordersChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
          label: 'Orders',
          data: {!! json_encode($chartData) !!},
          backgroundColor: 'rgba(138, 180, 248, 0.2)',
          borderColor: '#8ab4f8',
          borderWidth: 2,
          tension: 0.4,
          fill: true,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0,
              color: '#aaa'
            },
            grid: {
              color: '#333'
            }
          },
          x: {
            ticks: { color: '#aaa' },
            grid: { color: '#333' }
          }
        },
        plugins: {
          legend: {
            labels: { color: '#ccc' }
          }
        }
      }
    });
  </script>

</body>
</html>
