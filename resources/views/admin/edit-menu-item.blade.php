<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Menu Item - Admin</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto+Mono&display=swap" rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
      transition: 0.3s;
      text-decoration: none;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #8ab4f8;
      color: black;
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
    }

    .form-container {
      max-width: 700px;
      margin: auto;
      background: #1a1a1a;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(138, 180, 248, 0.1);
    }

    h1 {
      text-align: center;
      font-family: 'Orbitron', sans-serif;
      font-size: 2rem;
      color: #8ab4f8;
      margin-bottom: 1.5rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    label {
      font-weight: bold;
      color: #8ab4f8;
      display: block;
      margin-bottom: 8px;
    }

    input, select, textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #444;
      background: #121212;
      color: #ddd;
      border-radius: 6px;
      font-size: 1rem;
    }

    input[type="text"],
input[type="number"] {
  background: #121212;
  color: #ddd;
  border: 1px solid #444;
}

    input:focus, textarea:focus, select:focus {
      border-color: #8ab4f8;
      outline: none;
      box-shadow: 0 0 5px rgba(138, 180, 248, 0.5);
    }

    .image-preview {
      width: 200px;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
      display: block;
      margin: 0 auto 1.5rem;
    }

    .btn-row {
      display: flex;
      justify-content: space-between;
      margin-top: 2rem;
    }

    .btn {
      padding: 12px 24px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-update {
      background: #8ab4f8;
      color: black;
    }

    .btn-update:hover {
      background: #5f8ee2;
    }

    .btn-cancel {
      background: #e74c3c;
      color: white;
    }

    .btn-cancel:hover {
      background: #c0392b;
    }

    .btn-delete {
      background: #ff4c4c;
      color: white;
      margin-top: 1rem;
      width: 100%;
    }

    .btn-delete:hover {
      background: #b03c3c;
    }

    footer {
      text-align: center;
      padding: 1rem;
      background: #0c0c0c;
      color: #888;
      margin-top: 3rem;
    }
  </style>
</head>
<body x-data="{ sidebarOpen: true, showModal: false }">

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

  <main class="main-content">
    <h1>Edit Menu Item</h1>

    <div class="form-container">
      @if(session('success'))
        <div class="text-green-500 text-center mb-4 font-semibold">
          {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div class="text-red-500 mb-4">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.menu.update', $menuItem->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if($menuItem->image)
          <img id="preview" src="{{ asset('storage/' . $menuItem->image) }}" class="image-preview" alt="{{ $menuItem->title }}">
        @endif

        <div class="form-group">
          <label for="image">Update Photo</label>
          <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)">
        </div>

        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" name="title" id="title" value="{{ old('title', $menuItem->title) }}" required>
        </div>

        <div class="form-group">
          <label for="category">Category</label>
          <select name="category" id="category" required>
            @foreach(['Starters', 'Tandoori', 'Traditional', 'Special', 'Vegan', 'Sides', 'Rice', 'Naan', 'Sundries', 'Soft Drinks'] as $cat)
              <option value="{{ $cat }}" {{ old('category', $menuItem->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea name="description" id="description" rows="4">{{ old('description', $menuItem->description) }}</textarea>
        </div>

        <div class="form-group">
          <label for="price">Price (£)</label>
          <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $menuItem->price) }}" required>
        </div>

        <div class="form-group">
          <label>
            <input type="checkbox" name="is_featured" value="1" {{ $menuItem->is_featured ? 'checked' : '' }}>
            Featured Item
          </label>
        </div>

        <div class="btn-row">
          <a href="{{ route('admin.menu') }}" class="btn btn-cancel">Cancel</a>
          <button type="submit" class="btn btn-update">Update Item</button>
        </div>
      </form>

      <form action="{{ route('admin.menu.destroy', $menuItem->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-delete" @click="showModal = true">Delete Item</button>
        </form>
    </div>

    <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50" style="display: none;">
  <div class="bg-[#1a1a1a] text-white rounded-xl p-6 shadow-xl max-w-sm w-full border border-[#8ab4f8]">
    <h3 class="text-xl font-bold mb-4 text-[#8ab4f8]">Confirm Deletion</h3>
    <p class="mb-6 text-sm text-gray-300">Are you sure you want to delete this menu item? This action cannot be undone.</p>
    <div class="flex justify-end space-x-4">
      <button @click="showModal = false" class="px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 text-white font-semibold">Cancel</button>
      <form action="{{ route('admin.menu.destroy', $menuItem->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-4 py-2 rounded bg-red-600 hover:bg-red-500 text-white font-bold">Yes, Delete</button>
      </form>
    </div>
  </div>
</div>

  </main>

  <footer>
    &copy; {{ date('Y') }} Restaurant - Admin Panel. All rights reserved.
  </footer>

  <script>
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function () {
        document.getElementById('preview').src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>
</body>
</html>
