<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Us - Restaurant</title>
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

    /* Global Theme */
    .text-gold {
      color: #c4a000;
      font-family: 'Orbitron', sans-serif;
      letter-spacing: 1px;
    }

    .bg-dark {
      background-color: #121212;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 0 1rem;
    }

    /* Navigation */
    .nav-link {
      color: #8ab4f8;
      transition: 0.3s;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    .nav-link:hover {
      color: white;
    }

    /* Buttons */
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

    /* Hero */
    .hero-bg {
      background: url('{{ asset('images/about.jpg') }}') no-repeat center center;
      background-size: cover;
      position: relative;
    }

    .hero-bg::after {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    /* Section Titles */
    .section-title {
      font-size: 2.5rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 1rem;
      color: #c4a000;
      font-family: 'Orbitron', sans-serif;
    }

    /* Values Cards */
    .value-card {
      background: rgba(34, 34, 34, 0.95);
      padding: 24px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 2px 15px rgba(138, 180, 248, 0.1);
      transition: transform 0.3s;
    }
    .value-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(138, 180, 248, 0.3);
    }

    /* Footer */
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

<!-- ✅ Navigation Bar -->
<header class="bg-dark w-full fixed top-0 left-0 z-50 shadow-md">
  <div class="container px-6 py-4 flex items-center justify-between">
    <div class="text-3xl font-bold text-gold tracking-wide">
      <a href="{{ url('/') }}" class="nav-link">Restaurant</a>
    </div>
    <nav>
      <ul class="flex space-x-6">
        <li><a href="{{ url('/') }}" class="nav-link">Home</a></li>
        <li><a href="{{ url('/menu') }}" class="nav-link">Menu</a></li>
        <li><a href="{{ url('/order-online') }}" class="nav-link">Order Online</a></li>
        <li><a href="{{ url('/book-table') }}" class="nav-link">Reserve Table</a></li>
      </ul>
    </nav>
    <div>
      @if(Auth::check())
        <a href="{{ url('/dashboard') }}" class="gold-btn">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="gold-btn">Login</a>
      @endif
    </div>
  </div>
</header>

<!-- ✅ Hero Section -->
<section class="hero-bg w-full h-96 flex items-center justify-center mt-20">
  <div class="hero-content text-center px-6 py-8">
    <h1 class="text-6xl font-bold text-gold mb-4">
      About Restaurant
    </h1>
    <p class="text-xl text-gray-300">
      A celestial journey through flavors, inspired by the dunes and beyond.
    </p>
  </div>
</section>

<!-- ✅ Our Story -->
<section class="py-16 bg-dark">
  <div class="container">
    <h2 class="section-title">Our Story</h2>
    <p class="text-lg text-gray-300 leading-relaxed text-center max-w-3xl mx-auto">
      Born from a passion for exotic flavors and interstellar experiences, Restaurant is more than a dining destination—it's a voyage into taste, culture, and ambiance. 
      We blend ancient culinary traditions with modern techniques, serving dishes crafted with the finest ingredients, sourced from across the cosmos.
    </p>
  </div>
</section>

<!-- ✅ Mission & Values -->
<section class="py-16 bg-gray-900">
  <div class="container">
    <h2 class="section-title">Our Mission</h2>
    <p class="text-lg text-gray-300 leading-relaxed text-center max-w-3xl mx-auto mb-12">
      To create a dining experience that transports guests to another world—where taste meets artistry and ambiance.
    </p>

    <h2 class="section-title mb-8">Our Values</h2>
    <div class="grid gap-8 grid-cols-1 md:grid-cols-3 text-center">
      <div class="value-card">
        <h3 class="text-xl font-bold text-gold">Authenticity</h3>
        <p class="text-gray-300 mt-2">We stay true to the rich culinary traditions that inspire our menu.</p>
      </div>
      <div class="value-card">
        <h3 class="text-xl font-bold text-gold">Innovation</h3>
        <p class="text-gray-300 mt-2">Fusing tradition with cutting-edge techniques to create bold new flavors.</p>
      </div>
      <div class="value-card">
        <h3 class="text-xl font-bold text-gold">Sustainability</h3>
        <p class="text-gray-300 mt-2">We prioritize eco-friendly practices and ethical ingredient sourcing.</p>
      </div>
    </div>
  </div>
</section>

<!-- ✅ CTA Section -->
<section class="py-16 bg-dark text-center">
  <div class="container">
    <h2 class="section-title mb-4">Experience the Taste of the Restaurant</h2>
    <p class="text-lg text-gray-300 leading-relaxed max-w-3xl mx-auto mb-6">
      Join us for an unforgettable dining experience, where every dish tells a story of adventure.
    </p>
    <a href="{{ url('/menu') }}" class="gold-btn">
      Explore Our Menu
    </a>
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
