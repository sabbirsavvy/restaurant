<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order #{{ $order->id }} Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto+Mono&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      background-color: #0a0a0a;
      font-family: 'Roboto Mono', monospace;
      color: #d1d1d1;
      margin: 0;
      padding: 2rem;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(ellipse at center, rgba(138, 180, 248, 0.07), transparent 70%),
                  radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.03), transparent 50%);
      z-index: -1;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      background-color: #121212;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(138, 180, 248, 0.12);
    }

    h1 {
      font-family: 'Orbitron', sans-serif;
      color: #8ab4f8;
      text-align: center;
      font-size: 2rem;
      margin-bottom: 2rem;
    }

    h2 {
      color: #8ab4f8;
      font-size: 1.25rem;
      margin-top: 1.5rem;
      margin-bottom: 0.75rem;
    }

    .info-block p {
      margin-bottom: 0.4rem;
    }

    .status {
      font-weight: bold;
    }

    .status-accepted {
      color: #2ecc71;
    }

    .status-declined {
      color: #e74c3c;
    }

    .status-pending {
      color: #f1c40f;
    }

    .item {
      background-color: #1c1c1c;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      margin-bottom: 0.5rem;
      display: flex;
      justify-content: space-between;
    }

    .total {
      text-align: right;
      font-size: 1.5rem;
      font-weight: bold;
      color: #8ab4f8;
      margin-top: 1.5rem;
    }

    .btn {
      display: inline-block;
      padding: 0.6rem 1.2rem;
      background-color: #5f8ee2;
      color: black;
      font-weight: bold;
      border-radius: 6px;
      margin-top: 2rem;
      transition: background 0.3s;
      text-decoration: none;
    }

    .btn:hover {
      background-color: #3e70d3;
      color: white;
    }

    .back-link {
      margin-top: 1rem;
      display: inline-block;
      text-align: center;
      color: #aaa;
      font-size: 0.9rem;
    }

    .back-link:hover {
      color: #fff;
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>🌌 Order #{{ $order->id }} Details</h1>

    <!-- Order Info -->
    <div class="info-block">
      <p><strong>Placed On:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
      <p><strong>Status:</strong> 
        <span class="status {{ 
          $order->status == 'accepted' ? 'status-accepted' : 
          ($order->status == 'declined' ? 'status-declined' : 'status-pending') 
        }}">
          {{ ucfirst($order->status) }}
        </span>
      </p>
      <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
      <p><strong>Type:</strong> {{ ucfirst($order->type) }}</p>
      @if($order->schedule)
        <p><strong>Scheduled Time:</strong> {{ \Carbon\Carbon::parse($order->schedule)->format('D, j M • g:i A') }}</p>
      @endif
    </div>

    <!-- Customer Info -->
    <h2>Customer Info</h2>
    <div class="info-block">
      <p><strong>Name:</strong> {{ $order->name }}</p>
      <p><strong>Email:</strong> {{ $order->email }}</p>
      <p><strong>Phone:</strong> {{ $order->phone }}</p>
      @if($order->type === 'delivery')
        <p><strong>Address:</strong> {{ $order->address1 }}, {{ $order->address2 }}, {{ $order->city }}, {{ $order->county }}, {{ $order->postcode }}</p>
      @endif
    </div>

    <!-- Order Items -->
    <h2>Items Ordered</h2>
    <div>
      @foreach(json_decode($order->order_details, true) as $item)
        <div class="item">
          <span>{{ $item['title'] }} x{{ $item['quantity'] }}</span>
          <span>£{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
        </div>
      @endforeach
    </div>

    <!-- Total -->
    <div class="total">
      Total: £{{ number_format($order->total_amount, 2) }}
    </div>

    <!-- Invoice & Back Links -->
    <div class="text-center">
      <a href="{{ route('users.invoice', $order->id) }}" class="btn">📄 Download Invoice</a>
      <br>
      <a href="{{ route('dashboard') }}" class="back-link">← Back to Dashboard</a>
    </div>
  </div>

</body>
</html>
