<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Font Awesome -->
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
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 30% 30%, rgba(138, 180, 248, 0.08), transparent 40%),
                  radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.05), transparent 50%);
      z-index: -1;
    }

    .login-container {
      background: rgba(20, 20, 30, 0.95);
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 30px rgba(138, 180, 248, 0.15);
      width: 100%;
      max-width: 420px;
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

    .password-toggle {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #8ab4f8;
      cursor: pointer;
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

    .forgot-password,
    .register-link {
      margin-top: 20px;
      font-size: 0.9rem;
    }

    .forgot-password a,
    .register-link a {
      color: #8ab4f8;
      text-decoration: none;
      transition: 0.3s;
    }

    .forgot-password a:hover,
    .register-link a:hover {
      color: #ffffff;
      text-decoration: underline;
    }

    .quote {
      font-size: 0.8rem;
      color: #555;
      margin-top: 30px;
      font-style: italic;
    }

    .error-message {
      background-color: #2a0000;
      border-left: 4px solid #ff5555;
      color: #ffaaaa;
      padding: 12px 16px;
      font-size: 0.9rem;
      margin-bottom: 20px;
      text-align: left;
      border-radius: 6px;
    }
  </style>
</head>
<body>

<div class="login-container">
  <h2>Restaurant</h2>
  <p class="subtitle">Initiating gravitational login sequence...</p>

  <!-- Session Error Message -->
  @if(session('error'))
    <div class="error-message">
      {{ session('error') }}
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
      <input id="email" type="email" name="email" required autofocus placeholder=" " value="{{ old('email') }}">
      <label for="email">Email Address</label>
      @error('email')
        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group">
      <input id="password" type="password" name="password" required placeholder=" ">
      <label for="password">Password</label>
      <span class="password-toggle" onclick="togglePassword()">
        <i id="toggleIcon" class="fa-solid fa-eye"></i>
      </span>
      @error('password')
        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
      @enderror
    </div>

    <div class="checkbox-container">
      <input type="checkbox" name="remember" id="remember">
      <label for="remember">Remember me in this dimension</label>
    </div>

    <button type="submit" class="gold-btn">Log In</button>

    <p class="forgot-password">
      <a href="{{ route('password.request') }}">Forgot your password?</a>
    </p>

    <p class="register-link">
      Don’t have an account? <a href="{{ route('register') }}">Sign Up</a>
    </p>
  </form>

  <p class="quote">
    "Love is the one thing we're capable of perceiving that transcends dimensions of time and space." – Interstellar
  </p>
</div>

<!-- Password Toggle Script -->
<script>
  function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.getElementById("toggleIcon");
    if (password.type === "password") {
      password.type = "text";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      password.type = "password";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  }
</script>

</body>
</html>
