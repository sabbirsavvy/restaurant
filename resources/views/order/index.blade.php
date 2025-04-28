<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Menu - Dune Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://kit.fontawesome.com/your_kit_id.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
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
    .nav-link:hover { color: #ffffff; }
    .text-gold { color: #c4a000; font-family: 'Orbitron', sans-serif; letter-spacing: 1px; }
    .bg-dark { background-color: #121212; }
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
    }
    .menu-section {
      background-color: #1a1a1a;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .menu-section:hover { background-color: #222; }
    .menu-item {
      background-color: #1e1e1e;
      padding: 12px;
      border-radius: 10px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 15px;
      transition: transform 0.3s ease;
    }
    .menu-item:hover {
      transform: scale(1.03);
      box-shadow: 0 4px 20px rgba(138, 180, 248, 0.2);
    }
    .menu-image {
      width: 100px;
      height: 100px;
      border-radius: 8px;
      object-fit: cover;
    }
    .quantity-input {
  width: 60px;
  text-align: center;
  background: #222;
  border: 1px solid #8ab4f8;
  color: white;
  border-radius: 5px;
  padding: 8px 10px; /* <<< slightly more comfortable */
}

    footer {
      background: #0c0c0c;
      color: #666;
      padding: 1rem;
      text-align: center;
    }
    footer a {
      color: #8ab4f8;
      margin: 0 10px;
      text-decoration: none;
    }
    footer a:hover { color: white; }
    .container {
      max-width: 1200px;
      margin: auto;
      padding: 0 1rem;
    }
    .search-input {
  width: 100%;
  max-width: 400px;
  padding: 12px 18px; /* <<< slightly more padding */
  border-radius: 6px;
  border: 1px solid #333;
  background: #121212;
  color: #d1d1d1;
  font-family: 'Roboto Mono', monospace;
}


  </style>
</head>

<body class="bg-black text-gray-100 min-h-screen flex flex-col">

<header class="bg-dark w-full fixed top-0 left-0 z-50 shadow-md">
  <div class="container mx-auto px-6 py-4 flex items-center justify-between">
    <div class="text-3xl font-bold text-gold tracking-wide">
      <a href="{{ url('/') }}" class="nav-link">Restaurant</a>
    </div>
    <nav class="flex items-center space-x-6">
      <ul class="flex space-x-6">
        <li><a href="{{ url('/') }}" class="nav-link">Home</a></li>
        <li><a href="{{ url('/book-table') }}" class="nav-link">Reserve Table</a></li>
        <li><a href="{{ url('/about') }}" class="nav-link">About Us</a></li>
      </ul>
      <div class="ml-6">
        <a href="{{ route('cart.show') }}" class="gold-btn">
          <i class="fas fa-shopping-bag"></i> View Bag
        </a>
      </div>
    </nav>
  </div>
</header>




@php
  $desiredOrder = [
    'starter', 'tandoori', 'traditional', 'special',
    'vegan', 'sides', 'rice', 'naan', 'sundries', 'soft drinks'
  ];

  $menuCategories = collect($menuItems)
      ->sortBy(function ($item) use ($desiredOrder) {
          return array_search(strtolower($item->category), $desiredOrder);
      })
      ->groupBy('category');
@endphp

<section class="py-20 mt-20 bg-dark"
         x-data='{
            search: "",
            categories: @json($menuCategories->map->values())
         }'>
  <div class="container px-6">

    @if(session('success'))
    <div 
      x-data="{ show: true }" 
      x-init="setTimeout(() => show = false, 4000)" 
      x-show="show"
      x-transition
      class="bg-[#111927] text-green-400 border border-green-600 mb-6 mx-auto w-fit px-6 py-3 rounded-lg shadow-md font-semibold text-sm tracking-wide"
    >
      <i class="fas fa-check-circle mr-2 text-green-500"></i>
      {{ session('success') }}
    </div>
    @endif

    <h2 class="text-4xl font-bold text-center mb-10 text-gold">Order Online</h2>


    <!-- ✅ Search Box -->
<div class="flex justify-end mb-6">
  <input
    type="text"
    placeholder="Search for a dish..."
    x-model="search"
    class="search-input"
  />
</div>


    @php
      $desiredOrder = [
        'starter', 'tandoori', 'traditional', 'special',
        'vegan', 'sides', 'rice', 'naan', 'sundries', 'soft drinks'
      ];

      $menuCategories = collect($menuItems)
          ->sortBy(function ($item) use ($desiredOrder) {
              return array_search(strtolower($item->category), $desiredOrder);
          })
          ->groupBy('category');
    @endphp

    @foreach($menuCategories as $category => $items)
  <div x-data="{
    open: false,
    matches() {
      return categories[`{{ $category }}`].some(item =>
        item.title.toLowerCase().includes(search.toLowerCase())
      );
    }
  }"
  x-init="open = matches()"
  x-effect="open = matches()"
  >

        <div class="menu-section flex justify-between items-center text-white font-bold text-lg" @click="open = !open">
          <span class="text-gold">{{ ucfirst($category) }}</span>
          <span x-text="open ? '▲' : '▼'"></span>
        </div>
        <div x-show="open" class="menu-content mt-4">
        <template x-for="item in categories[`{{ $category }}`]" :key="item.id">
  <div x-show="item.title.toLowerCase().includes(search.toLowerCase())" class="menu-item">
              <template x-if="item.image">
                <img :src="'/storage/' + item.image" :alt="item.title" class="menu-image">
              </template>
              <template x-if="!item.image">
                <div class="w-24 h-24 bg-gray-700 flex items-center justify-center rounded-lg">
                  <span class="text-gray-500">No Image</span>
                </div>
              </template>

              <div class="flex-1">
                <h3 class="text-gold font-semibold" x-text="item.title"></h3>
                <p class="text-gray-300 text-sm" x-text="item.description"></p>
                <p class="text-gold font-bold">£<span x-text="parseFloat(item.price).toFixed(2)"></span></p>
              </div>

              <form :action="`{{ url('/order/add-to-cart') }}/${item.id}`" method="POST">
              @csrf
  <label class="text-gray-300">Qty:</label>
  <input type="number" name="quantity" value="1" min="1" class="quantity-input">
  <button type="submit" class="gold-btn mt-2 w-full max-w-full overflow-hidden">
    <i class="fas fa-shopping-bag"></i> Add to Bag
</button>

</form>

              </form>
            </div>
          </template>
        </div>
      </div>
    @endforeach
  </div>
</section>

<footer class="bg-black py-6">
  <div class="container mx-auto px-6 text-center text-gray-400">
    <p>&copy; {{ date('Y') }} Restaurant. Exploring galaxies of flavour.</p>
    <div class="mt-4">
      <a href="#">Facebook</a>
      <a href="#">Instagram</a>
      <a href="#">Twitter</a>
    </div>
  </div>
</footer>

</body>
</html>
