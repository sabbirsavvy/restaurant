<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout - Dune Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/your_kit_id.js" crossorigin="anonymous"></script>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      background-color: #0a0a0a;
      color: rgb(15, 14, 14);
      font-family: 'Roboto Mono', monospace;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: relative;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(ellipse at center, rgba(58, 123, 213, 0.15), transparent 70%),
                  radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.05), transparent 40%);
      z-index: -1;
    }

    .text-gold {
      color: #FFD700;
      font-family: 'Orbitron', sans-serif;
      letter-spacing: 1.2px;
    }

    .nav-link {
      color: #8ab4f8;
      transition: 0.3s;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .nav-link:hover {
      color: white;
    }

    .form-box {
      max-width: 700px;
      margin: 7rem auto 5rem;
      background: rgba(34, 34, 34, 0.9);
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(138, 180, 248, 0.2);
    }

    .form-title {
      font-size: 2rem;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    label {
      font-weight: bold;
      display: block;
      margin-bottom: 6px;
      color: #8ab4f8;
    }

    input,
    textarea,
    select {
      width: 100%;
      padding: 10px 14px;
      margin-bottom: 1.2rem;
      border-radius: 6px;
      background-color: #121212;
      color: rgb(62, 73, 109);
      border: 1px solid #333;
      transition: 0.3s;
      font-family: 'Roboto Mono', monospace;
    }

    input:focus,
    select:focus,
    textarea:focus {
      border-color: #8ab4f8;
      outline: none;
    }

    .gold-btn {
      background: linear-gradient(45deg, #8ab4f8, #5f8ee2);
      color: black;
      padding: 12px 24px;
      border-radius: 6px;
      font-weight: bold;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-transform: uppercase;
      transition: 0.3s;
      border: none;
      cursor: pointer;
    }

    .gold-btn:hover {
      background: linear-gradient(45deg, #5f8ee2, #1c4fd6);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(138, 180, 248, 0.3);
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
      text-decoration: none;
      margin: 0 10px;
    }

    footer a:hover {
      color: white;
    }

    .hidden {
      display: none;
    }

    .flatpickr-calendar {
  background: #1c1c1c;
  border: 1px solid #333;
  color: #8ab4f8;
  font-family: 'Roboto Mono', monospace;
}

.flatpickr-time {
  background-color: #0a0a0a;
}

.flatpickr-am-pm,
.flatpickr-time input {
  background-color: #121212;
  color: #FFD700;
  border: 1px solid #333;
  border-radius: 4px;
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

  <!-- ✅ Checkout Form -->
  <!-- ✅ Alpine Setup for Estimate Handling -->
<section class="form-box" 
         x-data="{
           orderType: 'collection',
           isOpen: {{ $isOpen ? 'true' : 'false' }},
           collectionEstimate: '{{ \Carbon\Carbon::parse($collectionEstimate)->format('D, jS M Y • g:i A') }}',
           deliveryEstimate: '{{ \Carbon\Carbon::parse($deliveryEstimate)->format('D, jS M Y • g:i A') }}',
           openingTime: '{{ \Carbon\Carbon::parse($openingHours[strtolower(now()->format('D'))]['open'])->format('g:i A') }}',
           closingTime: '{{ \Carbon\Carbon::parse($openingHours[strtolower(now()->format('D'))]['close'])->format('g:i A') }}'
         }">
  <h2 class="form-title text-gold">🚀 Finalise Your Order</h2>

  <form method="POST" action="{{ route('checkout.process') }}">
    @csrf

    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="{{ $user->name }}" required>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="{{ $user->email }}" required>

    <label for="phone">Phone</label>
    <input type="text" name="phone" id="phone" value="{{ $user->phone ?? '' }}" required>

    <label for="type">Order Type</label>
    <select name="type" id="orderType" x-model="orderType" required>
      <option value="collection">Collection</option>
      <option value="delivery">Delivery</option>
    </select>

    <!-- ✅ Timing Info -->
    <div class="mt-4 text-sm pb-4" x-show="!isOpen">
      <p class="text-yellow-400 font-semibold mb-2">
        We are currently closed. But you can place a preorder now.
      </p>
      <template x-if="orderType === 'collection'">
        <p class="text-yellow-300">
          🕓 Ready for <strong>Collection</strong> at <span x-text="collectionEstimate"></span>
        </p>
      </template>
      <template x-if="orderType === 'delivery'">
        <p class="text-yellow-300">
          🚗 Available for <strong>Delivery</strong> by <span x-text="deliveryEstimate"></span>
        </p>
      </template>
      <p class="text-gray-400 mt-1">
        (Opening hours: <span x-text="openingTime"></span> – <span x-text="closingTime"></span>)
      </p>
    </div>

    <div class="mt-4 text-sm pb-4" x-show="isOpen">
      <template x-if="orderType === 'collection'">
        <p class="text-green-400">
          🕓 Earliest <strong>Collection</strong>: <span x-text="collectionEstimate"></span>
        </p>
      </template>
      <template x-if="orderType === 'delivery'">
        <p class="text-green-400">
          🚗 Earliest <strong>Delivery</strong>: <span x-text="deliveryEstimate"></span>
        </p>
      </template>
    </div>

    <!-- ✅ Address Fields -->
    <div id="addressFields" class="hidden">
      <label for="address1">Address Line 1</label>
      <input type="text" name="address1" id="address1" value="{{ $user->address1 ?? '' }}">

      <label for="address2">Address Line 2 (Optional)</label>
      <input type="text" name="address2" id="address2" value="{{ $user->address2 ?? '' }}">

      <label for="city">City</label>
      <input type="text" name="city" id="city" value="{{ $user->city ?? '' }}">

      <label for="county">County</label>
      <input type="text" name="county" id="county" value="{{ $user->county ?? '' }}">

      <label for="postcode">Postcode</label>
      <input type="text" name="postcode" id="postcode" value="{{ $user->postcode ?? '' }}">
    </div>

    <div class="mb-4" x-data="{ showSchedule: false }">
  <div class="flex items-center gap-4 mb-2">
    <label for="scheduleToggle" class="text-sm text-gray-300">Schedule this order?</label>
    <button 
      type="button" 
      id="scheduleToggle"
      @click="showSchedule = !showSchedule"
      :class="showSchedule ? 'bg-blue-600' : 'bg-gray-700'" 
      class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none">
      <span 
        :class="showSchedule ? 'translate-x-6' : 'translate-x-1'" 
        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300"></span>
    </button>
  </div>

  <div x-show="showSchedule" x-transition>
    <label for="schedule" class="mt-3 block">Schedule Time</label>
    <input
  type="text"
  name="schedule"
  id="schedule"
  x-show="showSchedule"
  :required="showSchedule"
  placeholder="Choose a time"
  class="bg-black text-blue-200 placeholder-gray-500"
  autocomplete="off"
/>


  </div>
</div>



    <label for="payment_method">Payment Method</label>
    <select name="payment_method" id="payment_method" required>
      <option value="cash">Cash</option>
      <option value="card">Card</option>
    </select>

    <div class="text-center mt-6">
      <button type="submit" class="gold-btn">
        <i class="fas fa-check-circle"></i> Confirm & Continue
      </button>
    </div>
  </form>
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

  <!-- ✅ Toggle Address Script -->
  <script>
  const orderType = document.getElementById('orderType');
  const addressFields = document.getElementById('addressFields');

  const address1 = document.getElementById('address1');
  const city = document.getElementById('city');
  const county = document.getElementById('county');
  const postcode = document.getElementById('postcode');

  function toggleAddressFields() {
    const isDelivery = orderType.value === 'delivery';
    addressFields.classList.toggle('hidden', !isDelivery);

    // Dynamically set required attributes
    address1.required = isDelivery;
    city.required = isDelivery;
    county.required = isDelivery;
    postcode.required = isDelivery;
  }

  orderType.addEventListener('change', toggleAddressFields);
  window.addEventListener('DOMContentLoaded', toggleAddressFields);
</script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const orderTypeInput = document.getElementById('orderType');
    const scheduleInput = document.getElementById('schedule');

    function updateSchedulePicker(type) {
      const now = new Date();
      const today = now.toLocaleDateString('en-GB', { weekday: 'short' }).toLowerCase();

      // Define opening hours
      const openingHours = {
        mon: ['12:00', '23:00'],
        tue: ['12:00', '23:00'],
        wed: ['12:00', '23:00'],
        thu: ['12:00', '23:00'],
        fri: ['12:00', '23:00'],
        sat: ['12:00', '23:59'],
        sun: ['12:00', '22:00'],
      };

      const [openHour, openMin] = openingHours[today][0].split(':').map(Number);
      const [closeHour, closeMin] = openingHours[today][1].split(':').map(Number);

      const openTime = new Date();
      openTime.setHours(openHour, openMin, 0, 0);

      const closeTime = new Date();
      closeTime.setHours(closeHour, closeMin, 0, 0);

      // Define default, min, max time based on type
      let defaultTime, maxTime;

      if (type === 'collection') {
        defaultTime = new Date('{{ $collectionEstimate->format('Y-m-d H:i:s') }}');
        maxTime = new Date(closeTime.getTime() - 30 * 60000);
      } else {
        defaultTime = new Date('{{ $deliveryEstimate->format('Y-m-d H:i:s') }}');
        maxTime = new Date(closeTime.getTime() - 60 * 60000);
      }

      flatpickr(scheduleInput, {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        defaultDate: defaultTime,
        minTime: defaultTime,
        maxTime: maxTime
      });
    }

    // Initial run
    updateSchedulePicker(orderTypeInput.value);

    // Update picker when order type changes
    orderTypeInput.addEventListener('change', function () {
      updateSchedulePicker(this.value);
    });
  });
</script>




</body>
</html>
