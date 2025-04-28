<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order Details - Admin</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto+Mono&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      background-color: #0a0a0a;
      color: #d1d1d1;
      font-family: 'Roboto Mono', monospace;
      min-height: 100vh;
      margin: 0;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 30% 30%, rgba(138, 180, 248, 0.05), transparent 50%),
                  radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.04), transparent 50%);
      z-index: -1;
    }

    .container {
      max-width: 900px;
      margin: 3rem auto;
      padding: 2rem;
      background-color: #1a1a1a;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(138, 180, 248, 0.1);
    }

    h1 {
      font-family: 'Orbitron', sans-serif;
      color: #8ab4f8;
      font-size: 2rem;
      text-align: center;
      margin-bottom: 2rem;
    }

    .section {
      margin-bottom: 2rem;
    }

    .section h2 {
      color: #8ab4f8;
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
      border-bottom: 1px solid #444;
      padding-bottom: 0.3rem;
    }

    .info, .order-item {
      background-color: #121212;
      padding: 1rem;
      border-radius: 6px;
      margin-bottom: 0.75rem;
    }

    .info p {
      margin: 6px 0;
    }

    .order-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .item-name {
      font-weight: bold;
      color: #fff;
    }

    .item-qty, .item-price {
      color: #ccc;
      font-size: 0.95rem;
    }

    .back-btn {
      display: inline-block;
      margin-top: 2rem;
      padding: 10px 16px;
      background: #8ab4f8;
      color: black;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: 0.3s;
    }

    .back-btn:hover {
      background-color: #5f8ee2;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Order #{{ $order->id }} Details</h1>

    <div class="section">
  <h2>Customer Info</h2>
  <div class="info">
    <p><strong>Name:</strong> {{ $order->name ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>
    <p><strong>Phone:</strong> {{ $order->phone ?? 'N/A' }}</p>

    @if ($order->type === 'delivery')
      <p><strong>Address:</strong>
        {{ $order->address1 }},
        @if (!empty($order->address2)){{ $order->address2 }},@endif
        {{ $order->city }},
        {{ $order->county }},
        {{ $order->postcode }}
      </p>
    @endif
  </div>
</div>

    <div class="section">
      <h2>Order Summary</h2>
      <div class="info">
        <p><strong>Total Amount:</strong> £{{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Placed At:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
      </div>
    </div>

    <div class="section">
  <h2>Order Type & Payment</h2>
  <div class="info">
    <p><strong>Order Type:</strong> {{ ucfirst($order->type ?? 'N/A') }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
  </div>
</div>


    <div class="section">
      <h2>Items Ordered</h2>
      @foreach ($orderItems as $item)
        <div class="order-item">
          <div class="item-name">{{ $item['title'] }}</div>
          <div class="item-qty">Qty: {{ $item['quantity'] }}</div>
          <div class="item-price">£{{ number_format($item['price'], 2) }}</div>
        </div>
      @endforeach
    </div>

    <a href="{{ route('admin.order.history') }}" class="back-btn">← Back to Order History</a>
  </div>

</body>
</html>
