<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Bag - Dune Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/your_kit_id.js" crossorigin="anonymous"></script>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      margin: 0;
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
      background: radial-gradient(ellipse at center, rgba(58, 123, 213, 0.15), transparent 70%),
                  radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.05), transparent 40%);
      z-index: -1;
    }

    .nav-link {
      color: #8ab4f8;
      transition: 0.3s;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .nav-link:hover { color: white; }

    .text-gold {
      color: #FFD700;
      font-family: 'Orbitron', sans-serif;
      letter-spacing: 1.5px;
    }

    .bg-dark {
      background-color: #121212;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 0 1rem;
    }

    .gold-btn {
  background: linear-gradient(45deg, #8ab4f8, #5f8ee2);
  color: black;
  padding: 12px 24px; /* <<< from 10px 20px to 12px 24px */
  border-radius: 8px;
  font-weight: bold;
  transition: 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}


    .gold-btn:hover {
      background: linear-gradient(45deg, #5f8ee2, #1c4fd6);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(138, 180, 248, 0.4);
    }

    .bag-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .bag-table th, .bag-table td {
      padding: 12px;
      border-bottom: 1px solid #FFD700;
      text-align: center;
    }

    .bag-table th {
      background-color: #1c1c1c;
      color: #FFD700;
      font-size: 14px;
    }

    .bag-table td {
      background-color: #2a2a2a;
    }

    .remove-btn {
      background: #cc0000;
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: bold;
    }

    .remove-btn:hover { background: #e60000; }

    .clear-btn {
      background: #FFD700;
      color: black;
      padding: 10px 15px;
      border-radius: 6px;
      font-weight: bold;
      transition: 0.3s;
    }

    .clear-btn:hover {
      background: #e0b800;
      color: white;
    }

    .checkout-btn {
      background: #1E90FF;
      color: white;
      padding: 12px 20px;
      border-radius: 8px;
      font-weight: bold;
    }

    .checkout-btn:hover {
      background: #0070d1;
    }

    /* Modal Styling */
    .modal-bg {
      background: rgba(0, 0, 0, 0.85);
    }

    .modal-box {
      background: #121212;
      padding: 2rem;
      border-radius: 10px;
      text-align: center;
      color: white;
      border: 1px solid #8ab4f8;
      box-shadow: 0 0 20px rgba(138, 180, 248, 0.4);
    }

    .modal-btn {
      margin-top: 1rem;
      display: flex;
      justify-content: center;
      gap: 1rem;
    }

    .modal-btn button {
      padding: 8px 20px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
    }

    .modal-confirm {
      background: #5f8ee2;
      color: black;
    }

    .modal-cancel {
      background: #333;
      color: white;
    }

    footer {
      background: #0c0c0c;
      color: #666;
      padding: 1.5rem;
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
<body x-data="{ showModal: false, removeUrl: '', itemName: '', showClearModal: false }">

  <!-- ✅ Navbar -->
  <header class="bg-dark w-full fixed top-0 left-0 z-50 shadow-md">
    <div class="container px-6 py-4 flex items-center justify-between">
      <div class="text-3xl font-bold text-gold tracking-wide">
        <a href="{{ url('/') }}" class="nav-link">Restaurant</a>
      </div>
      <nav class="flex items-center space-x-6">
        <ul class="flex space-x-6">
          <li><a href="{{ url('/') }}" class="nav-link">Home</a></li>
          <li><a href="{{ url('/menu') }}" class="nav-link">Menu</a></li>
          <li><a href="{{ url('/order-online') }}" class="nav-link">Order Online</a></li>
          <li><a href="{{ url('/book-table') }}" class="nav-link">Reserve Table</a></li>
          <li><a href="{{ url('/about') }}" class="nav-link">About Us</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- ✅ View Bag -->
  <section class="py-20 mt-20 bg-dark">
    <div class="container px-6">
      <h2 class="text-4xl font-bold text-center mb-10 text-gold">Your Bag 🛍️</h2>

      @if(session('success'))
        <p class="text-green-500 text-center font-bold mb-6">{{ session('success') }}</p>
      @endif

      @if(!empty($cart) && count($cart) > 0)
        <div class="overflow-x-auto">
          <table class="bag-table">
            <thead>
              <tr>
                <th>Item</th>
                <th>Price (Each)</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @php $total = 0; @endphp
              @foreach($cart as $id => $details)
                @php
                  $subtotal = $details['price'] * $details['quantity'];
                  $total += $subtotal;
                @endphp
                <tr>
                  <td>{{ $details['title'] }}</td>
                  <td>£{{ number_format($details['price'], 2) }}</td>
                  <td>
  <div class="flex items-center justify-center space-x-2">
    <!-- Decrease -->
    <form action="{{ route('cart.update', $id) }}" method="POST">
      @csrf
      <input type="hidden" name="quantity" value="{{ $details['quantity'] - 1 }}">
      <button type="submit" class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-600 font-bold text-sm">−</button>
    </form>

    <!-- Display Quantity -->
    <span class="font-bold text-white">{{ $details['quantity'] }}</span>

    <!-- Increase -->
    <form action="{{ route('cart.update', $id) }}" method="POST">
      @csrf
      <input type="hidden" name="quantity" value="{{ $details['quantity'] + 1 }}">
      <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-500 font-bold text-sm">+</button>
    </form>
  </div>
</td>

                  <td>£{{ number_format($subtotal, 2) }}</td>
                  <td>
                    <button class="remove-btn"
                      @click="removeUrl = '{{ route('cart.remove', $id) }}'; itemName = '{{ $details['title'] }}'; showModal = true;">
                      Remove
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <h3 class="text-2xl font-bold text-center text-gold mt-6">Total: £{{ number_format($total, 2) }}</h3>

        <div class="flex justify-center mt-6 space-x-4">
          <button class="clear-btn" @click="showClearModal = true">Clear Bag 🗑️</button>

          <form action="{{ route('checkout.details') }}" method="GET">
    <button type="submit" class="checkout-btn">
        <i class="fas fa-shopping-bag"></i> Proceed to Checkout
    </button>
</form>

        </div>
      @else
        <p class="text-center text-gray-300">Your bag is empty.</p>
      @endif

      <div class="text-center mt-6">
        <a href="{{ route('order.index') }}" class="gold-btn">
          <i class="fas fa-arrow-left"></i> Continue Ordering
        </a>
      </div>
    </div>
  </section>

  <!-- ✅ Confirmation Modal (Remove Item) -->
  <div x-show="showModal" class="fixed inset-0 modal-bg flex items-center justify-center z-50" x-cloak>
    <div class="modal-box">
      <h2 class="text-2xl font-bold text-blue-400 mb-4">🚀 Are You Sure?</h2>
      <p class="text-gray-300">Remove "<span x-text="itemName"></span>" from your bag?</p>
      <div class="modal-btn">
        <a :href="removeUrl" class="modal-confirm gold-btn">Yes, Do it</a>
        <button class="modal-cancel" @click="showModal = false">Cancel</button>
      </div>
    </div>
  </div>

  <!-- ✅ Confirmation Modal (Clear Bag) -->
  <div x-show="showClearModal" class="fixed inset-0 modal-bg flex items-center justify-center z-50" x-cloak>
    <div class="modal-box">
      <h2 class="text-2xl font-bold text-blue-400 mb-4">🛸 Are You Sure?</h2>
      <p class="text-gray-300">Clear your entire bag?</p>
      <div class="modal-btn">
        <a href="{{ route('cart.clear') }}" class="modal-confirm gold-btn">Yes, Do it</a>
        <button class="modal-cancel" @click="showClearModal = false">Cancel</button>
      </div>
    </div>
  </div>

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
