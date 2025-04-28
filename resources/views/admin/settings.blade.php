<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Restaurant Settings - Admin</title>
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
      background: radial-gradient(circle, rgba(138, 180, 248, 0.05), transparent 60%),
                  radial-gradient(ellipse at center, rgba(255, 255, 255, 0.02), transparent 80%);
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
      cursor: pointer;
      z-index: 1100;
    }

    .main-content {
      margin-left: 250px;
      padding: 2rem;
      transition: margin-left 0.3s ease-in-out;
    }

    .content-collapsed {
      margin-left: 0;
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

    .settings-container {
      max-width: 900px;
      margin: 0 auto;
      background-color: #1a1a1a;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(138, 180, 248, 0.1);
    }

    h1 {
      font-family: 'Orbitron', sans-serif;
      font-size: 2rem;
      color: #8ab4f8;
      text-align: center;
      margin-bottom: 2rem;
    }

    label {
      font-weight: bold;
      color: #8ab4f8;
      margin-bottom: 0.5rem;
      display: block;
    }

    input[type="time"],
    input[type="number"] {
      background-color: #1e1e1e;
      border: 1px solid #333;
      color: #d1d1d1;
      padding: 0.6rem;
      border-radius: 6px;
      width: 100%;
      margin-bottom: 1rem;
    }

    .toggle {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .toggle input[type="checkbox"] {
      transform: scale(1.3);
    }

    .day-settings {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .save-btn {
      display: block;
      background-color: #8ab4f8;
      color: black;
      font-weight: bold;
      padding: 12px 24px;
      border: none;
      border-radius: 6px;
      margin: 2rem auto 0;
      transition: 0.3s;
      cursor: pointer;
    }

    .save-btn:hover {
      background-color: #5f8ee2;
      color: white;
    }
  </style>
</head>
<body x-data="{ sidebarOpen: true }">

  <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen">☰ Menu</button>

  <aside class="sidebar" :class="{'collapsed': !sidebarOpen}">
    <h2 style="text-align: center; color:#8ab4f8; font-family: Orbitron;">Restaurant</h2>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.settings') }}" class="active">Restaurant Settings</a>
    <a href="{{ route('admin.menu') }}">Manage Menu</a>
    <a href="{{ route('admin.orders') }}">Pending Orders</a>
    <a href="{{ route('admin.reservations') }}">Pending Reservations</a>
    <a href="{{ route('admin.order.history') }}">Order History</a>
    <a href="{{ route('admin.reservation.history') }}">Reservation History</a>
    <a href="{{ url('/') }}" class="text-red-400 mt-4 block">← Go to Website</a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="logout-btn">Logout</button>
    </form>
  </aside>

  <main class="main-content" :class="{'content-collapsed': !sidebarOpen}">
    <h1>⚙️ Restaurant Settings</h1>

    <div class="settings-container">
    <form method="POST" action="{{ route('admin.settings.save') }}">
    @csrf

        <div class="toggle">
          <input type="checkbox" name="is_open" {{ $settings['is_open'] ? 'checked' : '' }}>
          <label>Accepting Orders</label>
        </div>

        <div class="toggle">
          <input type="checkbox" name="preorders_enabled" {{ $settings['preorders_enabled'] ? 'checked' : '' }}>
          <label>Allow Preorders During Closing Hours</label>
        </div>

        <div class="toggle">
          <input type="checkbox" name="accepting_reservations" {{ $settings['accepting_reservations'] ? 'checked' : '' }}>
          <label>Accepting Reservations</label>
        </div>

        <label>Estimated Collection Time (minutes)</label>
        <input type="number" name="collection_time" value="{{ $settings['collection_time'] }}">

        <label>Estimated Delivery Time (minutes)</label>
        <input type="number" name="delivery_time" value="{{ $settings['delivery_time'] }}">


        <button type="submit" class="save-btn">💾 Save Settings</button>
      </form>
    </div>
  </main>
</body>
</html>
