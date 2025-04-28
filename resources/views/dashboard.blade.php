<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard - Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Fonts -->
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
      position: relative;
      overflow-x: hidden;
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

    .nav-link:hover {
      color: #ffffff;
    }

    .text-gold {
      color: #c4a000;
      font-family: 'Orbitron', sans-serif;
      letter-spacing: 1.5px;
    }

    .bg-dark {
      background-color: #121212;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 0 1rem;
    }

    h2 {
      font-family: 'Orbitron', sans-serif;
      letter-spacing: 1px;
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .card {
      background-color: rgba(34, 34, 34, 0.95);
      border: 1px solid #2a2a2a;
      border-radius: 10px;
      padding: 2rem;
      text-align: center;
      box-shadow: 0 0 15px rgba(138, 180, 248, 0.1);
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 0 30px rgba(138, 180, 248, 0.2);
    }

    .card h3 {
      font-size: 1.5rem;
      color: #8ab4f8;
      margin-bottom: 1rem;
    }

    .card a {
      display: inline-block;
      margin-top: 1rem;
      background: linear-gradient(45deg, #8ab4f8, #5f8ee2);
      color: #0a0a0a;
      padding: 10px 18px;
      border-radius: 6px;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }

    .card a:hover {
      background: linear-gradient(45deg, #5f8ee2, #1c4fd6);
      color: white;
    }

    .logout-form {
      margin-top: 3rem;
      text-align: center;
    }

    .logout-btn {
      color: #FF6B6B;
      background: none;
      border: none;
      font: inherit;
      font-weight: bold;
      cursor: pointer;
      padding: 10px 20px;
      transition: color 0.3s;
    }

    .logout-btn:hover {
      color: #ffffff;
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

<!-- ✅ Dashboard -->
<section class="py-24 mt-20 bg-dark">
  <div class="container px-6">
    <h2 class="text-4xl font-bold text-center mb-10 text-gold">Welcome, {{ auth()->user()->name }}!</h2>

    <div class="card-grid">
  <div class="card">
    <h3>Edit Profile</h3>
    <p>Update your name, email, or password anytime.</p>
    <a href="{{ route('profile.show') }}">Edit Now</a>
  </div>

  <div class="card">
    <h3>Your Reservations</h3>
    <p>See all your upcoming table bookings and their status.</p>
    <a href="{{ route('users.reservations') }}">View Reservations</a>
  </div>

  <div class="card">
    <h3>Your Orders</h3>
    <p>View your recent and past orders in descending order.</p>
    <a href="{{ route('users.orders') }}">View Orders</a>
  </div>
</div>

    </div>

    <!-- Logout -->
    <div class="logout-form">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>
</section>

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
