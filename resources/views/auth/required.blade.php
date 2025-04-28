<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Required</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      background-color: #0a0a0a;
      color: #d1d1d1;
      font-family: 'Roboto Mono', monospace;
      min-height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(ellipse at center, rgba(58, 123, 213, 0.1), transparent 70%),
                  radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.03), transparent 40%);
      z-index: -1;
    }

    .container {
      max-width: 700px;
      margin: 10% auto;
      background: rgba(18, 18, 18, 0.9);
      padding: 3rem 2rem;
      border-radius: 12px;
      box-shadow: 0 0 30px rgba(138, 180, 248, 0.2);
      text-align: center;
    }

    h1 {
      font-size: 2rem;
      font-family: 'Orbitron', sans-serif;
      color: #8ab4f8;
      margin-bottom: 1.5rem;
    }

    p {
      color: #aaa;
      font-size: 1rem;
      margin-bottom: 2rem;
    }

    .btn-group {
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    .interstellar-btn {
      padding: 12px 24px;
      font-weight: bold;
      font-family: 'Orbitron', sans-serif;
      font-size: 0.95rem;
      text-transform: uppercase;
      border-radius: 8px;
      background: linear-gradient(45deg, #8ab4f8, #5f8ee2);
      color: black;
      transition: all 0.3s ease-in-out;
      text-decoration: none;
      display: inline-block;
    }

    .interstellar-btn:hover {
      background: linear-gradient(45deg, #5f8ee2, #1c4fd6);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(138, 180, 248, 0.3);
    }

    footer {
      text-align: center;
      margin-top: auto;
      padding: 1.5rem;
      background: #0c0c0c;
      color: #666;
    }

  </style>
</head>
<body>

  <div class="container">
    <h1>Login Required to Proceed</h1>
    <p>To complete your order and checkout, please login or create an account.</p>

    <div class="btn-group">
      <a href="{{ route('login') }}" class="interstellar-btn">Login</a>
      <a href="{{ route('register') }}" class="interstellar-btn">Sign Up</a>
    </div>
  </div>

  <footer>
    &copy; {{ date('Y') }} Restaurant. Exploring galaxies of flavour.
  </footer>

</body>
</html>
