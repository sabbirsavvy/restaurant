<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Font Awesome for icons -->
  <script src="https://kit.fontawesome.com/your_kit_id.js" crossorigin="anonymous"></script>

  <!-- Custom Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <!-- Custom Styles -->
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
      background: radial-gradient(ellipse at center, rgba(58, 123, 213, 0.15), transparent 70%), radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.05), transparent 40%);
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

    .hero-bg {
      background: url('{{ asset('images/interstellar.jpg') }}') no-repeat center center;
      background-size: cover;
      position: relative;
      height: 100vh;
    }

    .hero-bg::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
    }

    .hero-content {
      position: relative;
      z-index: 1;
      text-align: center;
      padding: 2rem;
    }

    .hero-content h1 {
      font-size: 3rem;
      font-weight: bold;
      color: #e0e0e0;
      text-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
    }

    .hero-content p {
      margin-top: 1rem;
      font-size: 1.2rem;
      color: #aaa;
    }

    .gold-btn {
      background: linear-gradient(45deg, #8ab4f8, #5f8ee2);
      color: #000;
      padding: 12px 24px;
      border-radius: 6px;
      font-weight: bold;
      text-transform: uppercase;
      margin-top: 20px;
      display: inline-block;
      transition: all 0.3s;
    }

    .gold-btn:hover {
      background: linear-gradient(45deg, #5f8ee2, #1c4fd6);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(138, 180, 248, 0.4);
    }

    .gold-divider {
      width: 80%;
      height: 2px;
      background: linear-gradient(to right, transparent, #8ab4f8, transparent);
      margin: 3rem auto;
    }

    .section-heading {
      text-align: center;
      font-size: 2rem;
      font-weight: bold;
      color: #8ab4f8;
      margin-bottom: 2rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .info-section {
      display: flex;
      justify-content: center;
      align-items: stretch;
      gap: 40px;
      padding: 2rem 0;
      flex-wrap: wrap;
    }

    .info-box {
      background: #1c1c1c;
      border: 1px solid rgba(138, 180, 248, 0.3);
      border-radius: 8px;
      padding: 30px;
      flex: 1 1 300px;
      text-align: center;
      color: #d1d1d1;
      box-shadow: 0 0 15px rgba(138, 180, 248, 0.1);
    }

    .dish-card {
      background: #1a1a1a;
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      box-shadow: 0 4px 20px rgba(138, 180, 248, 0.1);
      transition: transform 0.3s;
    }

    .dish-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 30px rgba(138, 180, 248, 0.3);
    }

    .dish-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: auto;
    }

    .social-section {
      text-align: center;
      padding: 2rem 0;
    }

    .social-btn {
      display: inline-block;
      width: 50px;
      height: 50px;
      margin: 0 10px;
      filter: grayscale(100%) brightness(150%);
      transition: 0.3s;
    }

    .social-btn:hover {
      transform: scale(1.2);
      filter: brightness(200%);
    }

    .social-icon {
      width: 100%;
      height: auto;
    }

    footer {
      background: #0c0c0c;
      color: #666;
      padding: 1rem;
      text-align: center;
    }
  </style>
</head>
<body>

  <!-- ✅ Navbar -->
  <header class="bg-black w-full fixed top-0 left-0 z-50 shadow-md">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
      <!-- Logo -->
      <div class="text-3xl font-bold text-gold tracking-wide">
        <a href="{{ url('/') }}" class="nav-link">Restaurant</a>
      </div>

      <!-- Navigation + Auth Logic -->
      <nav class="flex items-center space-x-6">
        <ul class="flex space-x-6">
          <li><a href="{{ url('/menu') }}" class="nav-link">Menu</a></li>
          <li><a href="{{ url('/order-online') }}" class="nav-link">Order Online</a></li>
          <li><a href="{{ url('/book-table') }}" class="nav-link">Reserve Table</a></li>
          <li><a href="{{ url('/about') }}" class="nav-link">About Us</a></li>
        </ul>

        <!-- Auth Area -->
        <div class="ml-6">
        @if(Auth::check())
  @if(Auth::user()->email === 'sabbirsavvy@yahoo.com')
    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
  @else
    <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
  @endif
@else
  <a href="{{ route('login') }}" class="nav-link">Login / Sign-Up</a>
@endif

        </div>
      </nav>
    </div>
  </header>

  <!-- ✅ Hero -->
  <section class="hero-bg flex-grow w-full flex items-center justify-center relative">
    <div class="hero-content">
      <h1>Welcome to Restaurant</h1>
      <p>Across galaxies, we bring you flavours from beyond the stars.</p>
      <a href="{{ url('/menu') }}" class="gold-btn">Explore Our Menu</a>
    </div>
  </section>

  <div class="gold-divider"></div>

  <!-- ✅ Featured Dishes -->
  <section class="py-12 bg-dark">
    <h2 class="section-heading">Featured Dishes</h2>
    <div class="container mx-auto px-6 grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
      @foreach($featuredDishes as $dish)
        <div class="dish-card">
          <img src="{{ asset('storage/' . $dish->image) }}" alt="{{ $dish->title }}" class="w-full h-48 object-cover">
          <div class="p-6 flex-grow flex flex-col">
            <h3 class="text-xl font-semibold mb-2 text-gold">{{ $dish->title }}</h3>
            <p class="text-gray-300 flex-grow">{{ $dish->description }}</p>
            <div class="dish-footer">
              <p class="font-bold text-gold text-lg">£{{ number_format($dish->price, 2) }}</p>
              <a href="{{ url('/menu') }}" class="gold-btn">View Menu</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  <div class="gold-divider"></div>

  <!-- ✅ Info Section -->
  <section class="py-12 bg-dark">
    <div class="container mx-auto px-6 info-section">
      <div class="info-box">
        <h2 class="section-heading">Our Location</h2>
        <p>123 Cosmic Avenue<br>Space City, UK</p>
      </div>
      <div class="info-box">
        <h2 class="section-heading">Opening Hours</h2>
        <p>Mon - Fri: 12:00 PM - 11:00 PM</p>
        <p>Saturday: 12:00 PM - 12:00 AM</p>
        <p>Sunday: 12:00 PM - 10:00 PM</p>
      </div>
    </div>
  </section>

  <div class="gold-divider"></div>

  <!-- ✅ Social Media -->
  <section class="social-section">
    <h2 class="section-heading">Follow Us</h2>
    <a href="https://facebook.com" class="social-btn">
      <img src="{{ asset('images/facebook-icon.png') }}" alt="Facebook" class="social-icon">
    </a>
    <a href="https://instagram.com" class="social-btn">
      <img src="{{ asset('images/instagram-icon.png') }}" alt="Instagram" class="social-icon">
    </a>
  </section>

  <!-- ✅ Footer -->
  <footer>
    <p>&copy; {{ date('Y') }} Restaurant. Navigating the universe of flavours.</p>
  </footer>

</body>
</html>
