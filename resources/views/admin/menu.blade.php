@php
    $categoryOrder = [
        'Starters', 'Tandoori', 'Traditional', 'Special', 
        'Vegan', 'Sides', 'Rice', 'Naan', 'Sundries', 'Soft Drinks'
    ];

    $sortedMenuItems = $menuItems->sortBy(function ($item) use ($categoryOrder) {
        return array_search($item->category, $categoryOrder);
    });
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Menu - Admin</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Font Awesome & Alpine.js -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
      background: #ffffff;
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

    .menu-table {
      width: 100%;
      border-collapse: collapse;
      background: #1e1e1e;
      border-radius: 8px;
      overflow: hidden;
    }

    .menu-table th, .menu-table td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #2f2f2f;
    }

    .menu-table th {
      background-color: #2a2a2a;
      color: #8ab4f8;
      font-weight: 600;
      text-transform: uppercase;
    }

    .menu-table tbody tr:hover {
      background-color: rgba(58, 123, 213, 0.08);
    }

    .menu-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 8px;
    }

    .add-btn {
      background-color: #27ae60;
      color: white;
      padding: 10px 16px;
      font-weight: bold;
      border-radius: 8px;
      text-decoration: none;
      transition: 0.3s;
    }

    .add-btn:hover {
      background-color: #1e874b;
    }

    .action-container {
      display: flex;
      gap: 10px;
    }

    .action-btn {
      padding: 8px 14px;
      font-weight: bold;
      border-radius: 6px;
      text-align: center;
      width: 80px;
      display: inline-block;
      text-decoration: none;
    }

    .edit-btn {
      background-color: #3498db;
      color: white;
    }

    .edit-btn:hover {
      background-color: #2980b9;
    }

    .delete-btn {
      background-color: #e74c3c;
      color: white;
    }

    .delete-btn:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body x-data="{ sidebarOpen: true }">

  <!-- Sidebar Toggle Button -->
  <button @click="sidebarOpen = !sidebarOpen" class="toggle-btn">
    ☰ Menu
  </button>

  <!-- Sidebar Navigation -->
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
    <h1 class="page-title">Manage Menu</h1>

    <!-- Add Item Button -->
    <div class="text-right mb-4">
      <a href="{{ route('admin.menu.create') }}" class="add-btn">
        <i class="fas fa-plus"></i> Add New Item
      </a>
    </div>

    <!-- Menu Table -->
    <table class="menu-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Photo</th>
          <th>Title</th>
          <th>Category</th>
          <th>Price (£)</th>
          <th>Featured</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sortedMenuItems as $item)
          <tr>
            <td>{{ $item->id }}</td>
            <td>
              @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="menu-image" alt="{{ $item->title }}">
              @else
                <img src="{{ asset('storage/menu_items/default-placeholder.jpg') }}" class="menu-image" alt="No Image">
              @endif
            </td>
            <td>{{ $item->title }}</td>
            <td>{{ $item->category }}</td>
            <td>£{{ number_format($item->price, 2) }}</td>
            <td>{{ $item->is_featured ? 'Yes' : 'No' }}</td>
            <td>
              <div class="action-container">
                <a href="{{ route('admin.menu.edit', $item->id) }}" class="action-btn edit-btn">Edit</a>
                <form action="{{ route('admin.menu.destroy', $item->id) }}" method="POST" x-data>
  @csrf
  @method('DELETE')
  <button type="button" class="action-btn delete-btn"
          @click="$dispatch('open-delete-modal', { form: $el.closest('form') })">
    Delete
  </button>
</form>

              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Interstellar Themed Confirmation Modal -->
<div id="confirmModal" x-show="showModal" x-data="{ showModal: false, form: null }"
     @open-delete-modal.window="showModal = true; form = $event.detail.form"
     @keydown.escape.window="showModal = false"
     style="background-color: rgba(0,0,0,0.6);"
     class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
  <div class="bg-[#1b1b1b] p-6 rounded-lg shadow-lg max-w-sm w-full text-center border border-[#8ab4f8]">
    <h2 class="text-lg text-[#8ab4f8] font-bold mb-3">Delete Menu Item</h2>
    <p class="text-gray-300 mb-4">Are you sure you want to delete this item?</p>
    <div class="flex justify-center gap-4">
      <button @click="showModal = false"
              class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded">
        Cancel
      </button>
      <button @click="form.submit(); showModal = false"
              class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
        Yes, Delete
      </button>
    </div>
  </div>
</div>

  </main>
</body>
</html>
