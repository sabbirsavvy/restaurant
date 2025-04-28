<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add New Menu Item - Admin</title>
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
      text-decoration: none;
      transition: 0.3s;
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
      z-index: 1100;
      cursor: pointer;
    }

    .main-content {
      margin-left: 250px;
      padding: 3rem 2rem;
    }

    h1 {
      text-align: center;
      font-family: 'Orbitron', sans-serif;
      font-size: 2rem;
      color: #8ab4f8;
      margin-bottom: 2rem;
    }

    .form-container {
      max-width: 700px;
      margin: auto;
      background: #1a1a1a;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(138, 180, 248, 0.1);
    }

    .form-group {
      position: relative;
      margin-bottom: 1.75rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 14px;
      background: #121212;
      border: 1px solid #444;
      border-radius: 6px;
      color: #cfcfcf;
      font-size: 1rem;
      outline: none;
      transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: #8ab4f8;
      box-shadow: 0 0 5px rgba(138, 180, 248, 0.3);
    }

    .form-group label {
      position: absolute;
      top: 50%;
      left: 14px;
      transform: translateY(-50%);
      background: #1a1a1a;
      padding: 0 4px;
      color: #888;
      font-size: 0.9rem;
      pointer-events: none;
      transition: all 0.2s ease;
    }

    .form-group input:focus + label,
    .form-group input:not(:placeholder-shown) + label,
    .form-group textarea:focus + label,
    .form-group textarea:not(:placeholder-shown) + label {
      top: -10px;
      left: 10px;
      font-size: 0.75rem;
      color: #8ab4f8;
    }

    .image-preview {
      width: 160px;
      height: 160px;
      object-fit: cover;
      border-radius: 8px;
      display: block;
      margin: 0 auto 1.5rem;
      box-shadow: 0 0 10px rgba(138, 180, 248, 0.2);
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

    .btn-cancel {
      background: #e74c3c;
      color: white;
    }

    .btn-cancel:hover {
      background: #c0392b;
    }

    .btn-submit {
      background: linear-gradient(45deg, #8ab4f8, #5f8ee2);
      color: black;
    }

    .btn-submit:hover {
      background: linear-gradient(45deg, #5f8ee2, #1c4fd6);
      color: white;
    }

    .checkbox-row {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .checkbox-row label {
      margin: 0;
      color: #8ab4f8;
      font-size: 0.95rem;
    }

    footer {
      background: #0c0c0c;
      text-align: center;
      padding: 1rem;
      color: #666;
      margin-top: 3rem;
    }

    .success-message {
      text-align: center;
      margin-bottom: 1.5rem;
      animation: fadeSlideIn 0.4s ease-in-out;
    }

    .success-message div {
      display: inline-block;
      padding: 12px 24px;
      background-color: #2ecc71;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(46, 204, 113, 0.5);
    }

    @keyframes fadeSlideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body x-data="{ sidebarOpen: true }">

  <!-- Toggle Button -->
  <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen">☰ Menu</button>

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

  <!-- Main Form -->
  <main class="main-content">
    <h1>Add Menu Item</h1>

    <div class="form-container">
      @if(session('success'))
        <div class="success-message">
          <div>{{ session('success') }}</div>
        </div>
      @endif

      @if ($errors->any())
        <div class="text-red-500 mb-4">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <img id="preview" src="{{ asset('storage/menu_items/default-placeholder.jpg') }}" class="image-preview" alt="Preview">

        <div class="form-group">
          <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)">
          <label for="image">Upload Image</label>
        </div>

        <div class="form-group">
          <input type="text" name="title" id="title" placeholder=" " required value="{{ old('title') }}">
          <label for="title">Title</label>
        </div>

        <div class="form-group">
          <label for="category" style="position: static; margin-bottom: 8px; font-size: 0.95rem; color: #8ab4f8;">Category</label>
          <select name="category" id="category" required>
            <option disabled selected value="">Choose Category</option>
            @foreach(['Starters','Tandoori','Traditional','Special','Vegan','Sides','Rice','Naan','Sundries','Soft Drinks'] as $cat)
              <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <textarea name="description" id="description" placeholder=" " rows="4">{{ old('description') }}</textarea>
          <label for="description">Description</label>
        </div>

        <div class="form-group">
          <input type="number" name="price" id="price" placeholder=" " step="0.01" required value="{{ old('price') }}">
          <label for="price">Price (£)</label>
        </div>

        <div class="form-group checkbox-row" style="gap: 8px;">
  <input type="checkbox" name="is_featured" id="is_featured" value="1" style="width: 18px; height: 18px; accent-color: #8ab4f8;">
  <label for="is_featured" style="margin: 0; color: #8ab4f8; font-size: 0.95rem;">Featured Item</label>
</div>


        <div class="btn-row">
          <a href="{{ route('admin.menu') }}" class="btn btn-cancel">Cancel</a>
          <button type="submit" class="btn btn-submit">Add Item</button>
        </div>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; {{ date('Y') }} Dune Restaurant - Admin Panel. All rights reserved.</p>
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
