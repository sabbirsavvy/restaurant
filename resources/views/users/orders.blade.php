<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Orders - Dune Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
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
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(ellipse at center, rgba(58, 123, 213, 0.1), transparent 70%),
                  radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.03), transparent 40%);
      z-index: -1;
    }

    .nav-link {
      color: #8ab4f8;
      transition: 0.3s;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .nav-link:hover { color: #ffffff; }

    .text-gold {
      color: #c4a000;
      font-family: 'Orbitron', sans-serif;
      letter-spacing: 1.5px;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 2rem 1rem;
    }

    h1 {
      font-family: 'Orbitron', sans-serif;
      color: #8ab4f8;
      margin-bottom: 2rem;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: rgba(34, 34, 34, 0.95);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 15px rgba(138, 180, 248, 0.1);
    }

    th, td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #1e3a8a;
    }

    th {
      background-color: #1c1c1c;
      color: #8ab4f8;
      text-transform: uppercase;
      font-size: 14px;
      letter-spacing: 0.5px;
    }

    tr:hover {
      background-color: rgba(58, 123, 213, 0.1);
    }

    .action-link {
      color: #FFD700;
      font-weight: bold;
      text-decoration: underline;
    }

    .action-link:hover {
      color: white;
    }

    footer {
      background: #0c0c0c;
      color: #666;
      padding: 1rem;
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
<body>

<!-- ✅ Navbar -->
<header class="bg-black w-full fixed top-0 left-0 z-50 shadow-md">
  <div class="container mx-auto px-6 py-4 flex items-center justify-between">
    <div class="text-3xl font-bold text-gold tracking-wide">
      <a href="{{ url('/') }}" class="nav-link">Dune Restaurant</a>
    </div>
    <nav>
      <ul class="flex space-x-6">
        <li><a href="{{ url('/') }}" class="nav-link">Home</a></li>
        <li><a href="{{ url('/menu') }}" class="nav-link">Menu</a></li>
        <li><a href="{{ url('/order-online') }}" class="nav-link">Order Online</a></li>
        <li><a href="{{ url('/book-table') }}" class="nav-link">Reserve Table</a></li>
        <li><a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a></li>
      </ul>
    </nav>
  </div>
</header>

<!-- ✅ Orders Section -->
<main class="mt-32 container">
  <h1>🛒 Your Orders</h1>

  @if($orders->count())
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Order Type</th>
          <th>Placed At</th>
          <th>Total</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
          <tr>
            <td>{{ $order->id }}</td>
            <td>{{ ucfirst($order->type) }}</td>
            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
            <td>£{{ number_format($order->total_amount, 2) }}</td>
            <td>
              <a href="{{ route('users.viewdetails', ['orderId' => $order->id]) }}" class="action-link">View Details</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p class="text-gray-400 text-center mt-4">You haven't placed any orders yet.</p>
  @endif
</main>


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
