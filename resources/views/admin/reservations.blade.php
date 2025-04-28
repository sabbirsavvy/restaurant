@php
    $statuses = [
        'pending' => 'text-yellow-400',
        'accepted' => 'text-green-400',
        'declined' => 'text-red-400'
    ];
@endphp

<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: true, showModal: false, declineUrl: '' }">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Reservations - Admin</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto+Mono&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      background-color: #0a0a0a;
      font-family: 'Roboto Mono', monospace;
      color: #d1d1d1;
      margin: 0;
      min-height: 100vh;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(ellipse at center, rgba(58, 123, 213, 0.1), transparent 70%),
                  radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.03), transparent 40%);
      z-index: -1;
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

    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      background: #121212;
      padding: 70px 20px 20px;
      transition: transform 0.3s ease-in-out;
      overflow-y: auto;
      z-index: 1000;
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

    .sidebar a:hover, .sidebar a.active {
      background-color: #8ab4f8;
      color: #000;
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
      color: #8ab4f8;
      font-size: 2rem;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #1b1b1b;
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      padding: 14px;
      border-bottom: 1px solid #2f2f2f;
    }

    th {
      background: #222;
      color: #8ab4f8;
      font-weight: 600;
    }

    tr:hover {
      background-color: rgba(138, 180, 248, 0.05);
    }

    .action-btn {
      padding: 8px 14px;
      font-weight: bold;
      border-radius: 6px;
      margin-right: 6px;
      text-decoration: none;
      transition: 0.3s;
    }

    .accept-btn {
      background-color: #2ecc71;
      color: white;
    }

    .accept-btn:hover {
      background-color: #27ae60;
    }

    .decline-btn {
      background-color: #e74c3c;
      color: white;
    }

    .decline-btn:hover {
      background-color: #c0392b;
    }

    .status {
      font-weight: bold;
    }

    .empty-message {
      text-align: center;
      color: #bbb;
      font-size: 1.1rem;
      margin-top: 2rem;
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
    /* Modal styles */
    .modal-bg {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .modal {
      background: #1e1e1e;
      padding: 2rem;
      border-radius: 12px;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 0 20px rgba(138, 180, 248, 0.3);
    }

    .modal h3 {
      color: #8ab4f8;
      font-size: 1.2rem;
      margin-bottom: 1rem;
    }

    .modal .btn {
      margin: 0 8px;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }

    .modal .cancel {
      background: #e67e22;
      color: white;
    }

    .modal .confirm {
      background: #e74c3c;
      color: white;
    }

    .modal .cancel:hover {
      background: #d35400;
    }

    .modal .confirm:hover {
      background: #c0392b;
    }

    .collapsed {
  transform: translateX(-100%);
}
  </style>
</head>

<body>

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

  <main class="main-content" :class="{ 'content-collapsed': !sidebarOpen }">
    <h1 class="page-title">Manage Reservations</h1>

    @if(session('success'))
      <div class="text-green-500 text-center mb-4 font-semibold">
        {{ session('success') }}
      </div>
    @endif

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
            <th>Actions</th>
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
            <td class="status {{ $statuses[$reservation->status] ?? '' }}">{{ ucfirst($reservation->status) }}</td>
            <td>
              <a href="{{ route('admin.reservations.accept', $reservation->id) }}" class="action-btn accept-btn">Accept</a>
              <button @click="showModal = true; declineUrl = '{{ route('admin.reservations.decline', $reservation->id) }}'" class="action-btn decline-btn">
                Decline
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p class="empty-message">No pending reservations.</p>
    @endif
  </main>

  <!-- Alpine.js Confirmation Modal -->
  <div x-show="showModal" class="modal-bg" x-transition>
    <div class="modal">
      <h3>Are you sure you want to decline this reservation?</h3>
      <div>
        <button class="btn cancel" @click="showModal = false">Cancel</button>
        <a :href="declineUrl" class="btn confirm">Yes, Decline</a>
      </div>
    </div>
  </div>

</body>
</html>
