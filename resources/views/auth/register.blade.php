<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Fonts -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-PjZTIx9c7avEr0jI8DNvwOWqluFaJCS/5OMs6Ac5yzZTnGEyTu5rcgXLxyRfH4+p9bOOkonFkyRlfk2k+Z9B6g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto+Mono&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      margin: 0;
      background-color: #0a0a0a;
      color: #ffffff;
      font-family: 'Roboto Mono', monospace;
      height: 100vh;
      overflow-y: auto;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      padding: 2rem;
    }

    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 30% 30%, rgba(138, 180, 248, 0.08), transparent 40%),
                  radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.05), transparent 50%);
      z-index: -1;
    }

    .register-container {
      background: rgba(20, 20, 30, 0.95);
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 30px rgba(138, 180, 248, 0.15);
      width: 100%;
      max-width: 520px;
      text-align: center;
    }

    h2 {
      font-family: 'Orbitron', sans-serif;
      color: #8ab4f8;
      font-size: 2rem;
      margin-bottom: 8px;
      letter-spacing: 1px;
    }

    .subtitle {
      font-size: 0.9rem;
      color: #aaa;
      margin-bottom: 30px;
      font-style: italic;
    }

    .form-group {
      position: relative;
      margin-bottom: 24px;
      text-align: left;
    }

    .form-group label {
      position: absolute;
      top: 14px;
      left: 14px;
      font-size: 0.85rem;
      color: #888;
      transition: 0.3s;
      pointer-events: none;
    }

    input {
      width: 100%;
      padding: 16px;
      background: #1a1a1a;
      border: 1px solid #444;
      border-radius: 6px;
      color: #8ab4f8;
      font-size: 1rem;
      outline: none;
    }

    input:focus {
      border-color: #8ab4f8;
      background-color: #222;
    }

    input:focus + label,
    input:not(:placeholder-shown) + label {
      top: -10px;
      left: 10px;
      font-size: 0.75rem;
      color: #8ab4f8;
      background-color: #14141e;
      padding: 0 4px;
    }

    .checkbox-container {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
      font-size: 0.9rem;
    }

    .checkbox-container input {
      accent-color: #8ab4f8;
      width: 18px;
      height: 18px;
    }

    .gold-btn {
      background: linear-gradient(45deg, #8ab4f8, #5f8ee2);
      color: black;
      padding: 14px;
      border-radius: 6px;
      font-weight: bold;
      width: 100%;
      font-size: 1rem;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
    }

    .gold-btn:hover {
      background: linear-gradient(45deg, #5f8ee2, #1c4fd6);
      color: white;
      transform: translateY(-2px);
    }

    .login-link {
      margin-top: 20px;
      font-size: 0.9rem;
    }

    .login-link a {
      color: #8ab4f8;
      text-decoration: none;
      transition: 0.3s;
    }

    .login-link a:hover {
      color: #ffffff;
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="register-container">
  <h2>Join the Club</h2>
  <p class="subtitle">We're registering you across dimensions. Hold tight.</p>

  <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
      <input id="name" type="text" name="name" required autofocus placeholder=" ">
      <label for="name">Full Name</label>
    </div>

    <div class="form-group">
      <input id="email" type="email" name="email" required placeholder=" ">
      <label for="email">Email Address</label>
    </div>

    <div class="form-group">
      <input id="phone" type="text" name="phone" required placeholder=" ">
      <label for="phone">Phone Number</label>
    </div>

    <div class="form-group">
      <input id="address_line1" type="text" name="address_line1" required placeholder=" ">
      <label for="address_line1">Address Line 1</label>
    </div>

    <div class="form-group">
      <input id="address_line2" type="text" name="address_line2" placeholder=" ">
      <label for="address_line2">Address Line 2</label>
    </div>

    <div class="form-group">
      <input id="town" type="text" name="town" required placeholder=" ">
      <label for="town">Town</label>
    </div>

    <div class="form-group">
      <input id="county" type="text" name="county" required placeholder=" ">
      <label for="county">County</label>
    </div>

    <div class="form-group">
      <input id="postcode" type="text" name="postcode" required placeholder=" ">
      <label for="postcode">Postcode</label>
    </div>

    <div class="form-group">
      <input id="password" type="password" name="password" required placeholder=" ">
      <label for="password">Password</label>
    </div>

    <div class="form-group">
      <input id="password_confirmation" type="password" name="password_confirmation" required placeholder=" ">
      <label for="password_confirmation">Confirm Password</label>
    </div>

    <div class="checkbox-container">
      <input type="checkbox" name="remember" id="remember">
      <label for="remember">Remember Me</label>
    </div>

    <button type="submit" class="gold-btn">Register</button>

    <p class="login-link">
      Already registered? <a href="{{ route('login') }}">Login</a>
    </p>
  </form>
</div>

</body>
</html>
