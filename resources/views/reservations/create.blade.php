<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Book a Table - Dune Restaurant</title>
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

    /* Form Styling */
    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      font-weight: bold;
      margin-bottom: 8px;
      color: #c4a000;
    }

    .form-input {
      width: 100%;
      padding: 12px;
      background-color: rgba(34, 34, 34, 0.9);
      border: 1px solid #8ab4f8;
      color: white;
      border-radius: 8px;
      outline: none;
      transition: border 0.3s, box-shadow 0.3s;
    }

    .form-input:focus {
      border-color: #1e90ff;
      box-shadow: 0 0 12px rgba(138, 180, 248, 0.6);
    }

    /* Messages */
    .success-message {
      color: #00ff88;
      font-weight: bold;
      margin-bottom: 15px;
      text-align: center;
    }

    .error-message {
      color: #ff4c4c;
      font-weight: bold;
      margin-bottom: 15px;
      text-align: center;
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

    .flatpickr-calendar {
  font-size: 16px;
  background-color: #121212 !important;
  color: #8ab4f8;
  border: 1px solid #333;
  border-radius: 10px;
  box-shadow: 0 0 20px rgba(138, 180, 248, 0.2);
}

.flatpickr-day.today {
  background: rgba(255, 255, 255, 0.15) !important;
  color: #FFD700 !important;
  border-radius: 50%;
  font-weight: bold;
}

.flatpickr-day.selected {
  background: #8ab4f8 !important;
  color: #0a0a0a !important;
  font-weight: bold;
  border-radius: 50%;
}


.flatpickr-months {
  background-color: #1f1f1f;
  border-bottom: 1px solid #333;
}

<!-- Flatpickr Clock Styles -->
<style>
  .flatpickr-time {
    background-color: #121212;
    border-top: 1px solid #333;
  }

  .flatpickr-time input {
    background-color: #0a0a0a;
    color: #FFD700;
    font-family: 'Orbitron', sans-serif;
    font-size: 18px;
    border: 1px solid #333;
    border-radius: 4px;
  }

  .flatpickr-time .flatpickr-am-pm {
    background-color: #0a0a0a;
    color: #8ab4f8;
    border-radius: 4px;
    border: 1px solid #333;
  }

  .flatpickr-time-separator {
    color: #8ab4f8;
  }
</style>



  </style>

  <!-- Flatpickr CSS and Dark Theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

</head>
<body>

<!-- ✅ Navigation Bar -->
<header class="bg-grey w-full fixed top-0 left-0 z-50 shadow-md">
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
          <li><a href="{{ url('/about') }}" class="nav-link">About Us</a></li>
        </ul>

        <!-- Auth Area -->
        <div class="ml-6">
          @if(Auth::check())
            <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
          @else
            <a href="{{ route('login') }}" class="nav-link">Login / Sign-Up</a>
          @endif
        </div>
      </nav>
    </div>
  </header>

<!-- ✅ Reservation Section -->
<section class="py-16 mt-24 bg-dark">
  <div class="container">
    <h2 class="text-4xl font-bold text-center mb-10 text-gold">Book a Table</h2>

    <!-- ✅ Success & Error Messages -->
    @if(session('success'))
      <p class="success-message">{{ session('success') }}</p>
    @endif

    @if($errors->any())
      <div class="error-message">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- ✅ Reservation Form -->
    <form action="{{ route('reservations.store') }}" method="POST" class="max-w-2xl mx-auto bg-gray-800 p-8 rounded-lg shadow-lg">
  @csrf

  @php
    $user = Auth::user();
    $today = now()->toDateString();
  @endphp

  <div class="form-group">
    <label for="name" class="form-label">Full Name</label>
    <input type="text" name="name" id="name" class="form-input" required
           value="{{ old('name', $user->name ?? '') }}" placeholder="Enter your full name">
  </div>

  <div class="form-group">
    <label for="email" class="form-label">Email Address</label>
    <input type="email" name="email" id="email" class="form-input" required
           value="{{ old('email', $user->email ?? '') }}" placeholder="Enter your email">
  </div>

  <div class="form-group">
    <label for="phone" class="form-label">Phone Number</label>
    <input type="text" name="phone" id="phone" class="form-input" required
           value="{{ old('phone', $user->phone ?? '') }}" placeholder="Enter your phone number">
  </div>

  <div class="form-group">
    <label for="date" class="form-label">Reservation Date</label>
    <input type="date" name="date" id="date" class="form-input" required value="{{ old('date', $today) }}">
  </div>

  <div class="form-group">
  <label for="time" class="form-label">Time of Reservation</label>
  <input type="text" name="time" id="time" class="form-input" placeholder="Select a time" required>
  <small class="text-xs text-gray-400">Mon–Fri: 12:00–22:00, Sat: 12:00–22:30, Sun: 12:00–20:30</small>
</div>


  <div class="form-group">
    <label for="number_of_guests" class="form-label">Number of Guests</label>
    <input type="number" name="number_of_guests" id="number_of_guests" class="form-input" min="1" required placeholder="e.g. 2" value="{{ old('number_of_guests') }}">
  </div>

  <div class="form-group">
    <label for="special_requests" class="form-label">Special Requests (Optional)</label>
    <textarea name="special_requests" id="special_requests" rows="3" class="form-input" placeholder="Let us know if you have any special requirements">{{ old('special_requests') }}</textarea>
  </div>

  <button type="submit" class="gold-btn">Book Now</button>
</form>
  </div>
</section>

<!-- ✅ Footer -->
<footer>
  <p>&copy; {{ date('Y') }} Restaurant. All rights reserved.</p>
  <div class="mt-2">
    <a href="#">Facebook</a>
    <a href="#">Instagram</a>
    <a href="#">Twitter</a>
  </div>
</footer>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  flatpickr("#date", {
    dateFormat: "Y-m-d",
    minDate: "today",
    defaultDate: new Date(),
    disableMobile: "true", // ensures desktop calendar always shows
    wrap: false
  });
</script>
<script>
  const openingTimes = {
    '0': { min: "12:00", max: "20:30" }, // Sunday
    '1': { min: "12:00", max: "21:30" }, // Monday
    '2': { min: "12:00", max: "21:30" }, // Tuesday
    '3': { min: "12:00", max: "21:30" }, // Wednesday
    '4': { min: "12:00", max: "21:30" }, // Thursday
    '5': { min: "12:00", max: "21:30" }, // Friday
    '6': { min: "12:00", max: "22:30" }  // Saturday
  };

  flatpickr("#time", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    disableMobile: true,
    defaultHour: 18,
    defaultMinute: 0,
    onReady: function (selectedDates, dateStr, instance) {
      const today = new Date();
      const day = today.getDay(); // 0-6
      const { min, max } = openingTimes[day];

      instance.set('minTime', min);
      instance.set('maxTime', max);
    },
    onOpen: function(selectedDates, dateStr, instance) {
      const dateInput = document.getElementById('date');
      const dateValue = dateInput.value || new Date().toISOString().split('T')[0];
      const selectedDay = new Date(dateValue).getDay();
      const { min, max } = openingTimes[selectedDay];
      instance.set('minTime', min);
      instance.set('maxTime', max);
    }
  });
</script>



</body>
</html>
