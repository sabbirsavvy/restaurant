<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Profile - Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/your_kit_id.js" crossorigin="anonymous"></script>
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
      letter-spacing: 1.2px;
    }

    .bg-dark {
      background-color: #121212;
    }

    .profile-container {
      max-width: 700px;
      margin: auto;
      padding: 40px;
      background: rgba(18, 18, 18, 0.95);
      border-radius: 12px;
      box-shadow: 0px 10px 30px rgba(138, 180, 248, 0.3);
      backdrop-filter: blur(8px);
    }

    .profile-form {
      background: transparent;
      padding: 20px 0;
    }

    .form-label {
      color: #8ab4f8;
      font-weight: bold;
      display: block;
      margin-bottom: 4px;
    }

    .form-input {
      background: rgba(34, 34, 34, 0.9);
      border: 1px solid #8ab4f8;
      color: #fff;
      border-radius: 6px;
      padding: 12px;
      width: 100%;
      margin-bottom: 16px;
      transition: border 0.3s;
    }

    .form-input:focus {
      border-color: #5f8ee2;
      outline: none;
      box-shadow: 0 0 10px rgba(138, 180, 248, 0.3);
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

    .text-red-500 {
      color: #ff4c4c;
    }

    .bg-red-600 {
      background: #ff4c4c;
      color: white;
    }

    .bg-red-600:hover {
      background: #e60000;
    }

    .text-center {
      text-align: center;
    }

    .mt-4 { margin-top: 1rem; }
    .mt-6 { margin-top: 1.5rem; }
    .mt-10 { margin-top: 2.5rem; }
    .mb-6 { margin-bottom: 1.5rem; }

    /* Footer */
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

    footer a:hover {
      color: white;
    }

  </style>
</head>
<body>

  <!-- ✅ Navbar -->
  <header class="bg-dark w-full fixed top-0 left-0 z-50 shadow-md">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
      <div class="text-3xl font-bold text-gold tracking-wide">
        Restaurant
      </div>
      <nav>
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

  <!-- ✅ Profile Section -->
  <section class="py-20 mt-20 bg-dark">
    <div class="profile-container">
      <h2 class="text-3xl font-bold text-center text-gold mb-6">Edit Profile</h2>

      <!-- ✅ Update Profile Form -->
      <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
        @csrf

        <label for="name" class="form-label">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-input" required>

        <label for="email" class="form-label">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-input" required>

        <label for="phone" class="form-label">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-input" placeholder="Optional">

        <label for="address1" class="form-label">Address Line 1:</label>
        <input type="text" id="address1" name="address1" value="{{ old('address1', auth()->user()->address1) }}" class="form-input">

        <label for="address2" class="form-label">Address Line 2 (Optional):</label>
        <input type="text" id="address2" name="address2" value="{{ old('address2', auth()->user()->address2) }}" class="form-input">

        <label for="city" class="form-label">Town/City:</label>
        <input type="text" id="city" name="city" value="{{ old('city', auth()->user()->city) }}" class="form-input">

        <label for="county" class="form-label">County:</label>
        <input type="text" id="county" name="county" value="{{ old('county', auth()->user()->county) }}" class="form-input">

        <label for="postcode" class="form-label">Postcode:</label>
        <input type="text" id="postcode" name="postcode" value="{{ old('postcode', auth()->user()->postcode) }}" class="form-input">

        <button type="submit" class="gold-btn mt-4">
          <i class="fas fa-save"></i> Save Changes
        </button>
      </form>

      <!-- ✅ Update Password Form -->
      <h3 class="text-2xl font-bold text-center text-gold mt-10">Update Password</h3>
      <form action="{{ route('password.update') }}" method="POST" class="profile-form mt-4">
        @csrf

        <label for="current_password" class="form-label">Current Password:</label>
        <input type="password" id="current_password" name="current_password" class="form-input" required>

        <label for="new_password" class="form-label">New Password:</label>
        <input type="password" id="new_password" name="new_password" class="form-input" required>

        <label for="confirm_password" class="form-label">Confirm Password:</label>
        <input type="password" id="confirm_password" name="password_confirmation" class="form-input" required>

        <button type="submit" class="gold-btn mt-4">
          <i class="fas fa-lock"></i> Update Password
        </button>
      </form>

      <!-- ✅ Delete Account -->
      <h3 class="text-2xl font-bold text-center text-red-500 mt-10">Delete Account</h3>
      <form action="{{ route('profile.delete') }}" method="POST" class="profile-form mt-4">
        @csrf
        <p class="text-gray-400 text-center mb-4">Warning: This action is irreversible!</p>
        <button type="submit" class="gold-btn bg-red-600 hover:bg-red-800">
          <i class="fas fa-trash"></i> Delete Account
        </button>
      </form>
    </div>
  </section>

  <!-- ✅ Footer -->
  <footer>
    <p>&copy; {{ date('Y') }} Dune Restaurant. Navigating the universe of flavours.</p>
    <div class="mt-2">
      <a href="#">Facebook</a>
      <a href="#">Instagram</a>
      <a href="#">Twitter</a>
    </div>
  </footer>

</body>
</html>
