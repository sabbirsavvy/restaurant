<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Thank You - Dune Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/your_kit_id.js" crossorigin="anonymous"></script>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      background-color: #0a0a0a;
      color: #d1d1d1;
      font-family: 'Roboto Mono', monospace;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      min-height: 100vh;
      flex-direction: column;
      position: relative;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(circle, rgba(138, 180, 248, 0.05) 10%, transparent 70%),
                  radial-gradient(ellipse at center, rgba(255, 255, 255, 0.03), transparent 80%);
      z-index: -1;
    }

    h1 {
      font-size: 2.5rem;
      color: #8ab4f8;
      font-family: 'Orbitron', sans-serif;
      margin-bottom: 1rem;
    }

    .highlight {
      color: #FFD700;
    }

    .info {
      margin-top: 1.5rem;
      background: rgba(255, 255, 255, 0.03);
      padding: 1.5rem 2rem;
      border-radius: 12px;
      max-width: 700px;
      line-height: 1.6;
      box-shadow: 0 0 25px rgba(138, 180, 248, 0.08);
    }

    .back-btn {
      margin-top: 2rem;
      background-color: #8ab4f8;
      color: black;
      padding: 12px 24px;
      font-weight: bold;
      border-radius: 6px;
      transition: 0.3s;
      text-decoration: none;
      display: inline-block;
    }

    .back-btn:hover {
      background-color: #5f8ee2;
      color: white;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

  <h1>🎉 Thank you for your order!</h1>

  <div class="info">
  <p>Your order has been successfully placed.</p>

  @if($preorder)
    <p class="text-yellow-400 mt-4">
      <strong>We are currently closed.</strong><br>
      We'll reopen at <span class="highlight">{{ $openTime }}</span>.<br>
      @if($order->type === 'collection')
        Your food will be ready to <strong>collect</strong> by 
        <span class="highlight">{{ $estimateTime }}</span>.
      @else
        Your food will be <strong>delivered</strong> by 
        <span class="highlight">{{ $estimateTime }}</span>.
      @endif
    </p>
  @else
    <p class="text-green-400 mt-4">
      @if($order->type === 'collection')
        Your food will be ready to <strong>collect</strong> by 
        <span class="highlight">{{ $estimateTime }}</span>.
      @else
        Your food will be <strong>delivered</strong> by 
        <span class="highlight">{{ $estimateTime }}</span>.
      @endif
    </p>
  @endif
</div>


  <a href="{{ url('/') }}" class="back-btn">← Return to Home</a>

</body>
</html>
