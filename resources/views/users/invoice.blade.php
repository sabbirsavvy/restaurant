<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <meta charset="UTF-8">
  <title>Invoice #{{ $order->id }} - Restaurant</title>
  <style>
    body {
      font-family: 'DejaVu Sans', sans-serif;
      background-color: #0a0a0a;
      color: #d1d1d1;
      padding: 40px;
      font-size: 14px;
    }

    .header, .footer {
      text-align: center;
      margin-bottom: 30px;
    }

    .header h1 {
      color: #8ab4f8;
      font-size: 28px;
      margin-bottom: 4px;
    }

    .contact-info {
      font-size: 13px;
      color: #bbb;
    }

    .section-title {
      color: #c4a000;
      margin-top: 40px;
      font-size: 18px;
      border-bottom: 1px solid #333;
      padding-bottom: 6px;
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #1b1b1b;
      border-radius: 8px;
      overflow: hidden;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #2f2f2f;
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: #222;
      color: #8ab4f8;
      font-weight: bold;
    }

    .total {
      text-align: right;
      font-size: 16px;
      margin-top: 20px;
      font-weight: bold;
      color: #8ab4f8;
    }

    .footer {
      font-size: 13px;
      color: #999;
      margin-top: 60px;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Restaurant</h1>
    <div class="contact-info">
    123 Cosmic Avenue
    Space City, UK<br>
      Email: info@restaurant.co.uk  <br>Phone: +44 1234 567890<br>
      VAT Number: GB123456789
    </div>
  </div>

  <div>
    <div class="section-title">Invoice Details</div>
    <p><strong>Invoice #:</strong> {{ $order->id }}</p>
    <p><strong>Name:</strong> {{ $order->name }}</p>
    <p><strong>Email:</strong> {{ $order->email }}</p>
    <p><strong>Phone:</strong> {{ $order->phone }}</p>
    <p><strong>Order Type:</strong> {{ ucfirst($order->type) }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('D, j M Y - H:i') }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
    @if($order->type === 'delivery')
  <div class="section-title">Delivery Address</div>
  <p><strong>Address Line 1:</strong> {{ $order->address1 }}</p>
  @if($order->address2)
    <p><strong>Address Line 2:</strong> {{ $order->address2 }}</p>
  @endif
  <p><strong>City:</strong> {{ $order->city }}</p>
  <p><strong>County:</strong> {{ $order->county }}</p>
  <p><strong>Postcode:</strong> {{ $order->postcode }}</p>
@endif

  </div>

  <div class="section-title">Order Summary</div>
  <table>
    <thead>
      <tr>
        <th>Item</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach(json_decode($order->order_details, true) as $item)
      <tr>
        <td>{{ $item['title'] }}</td>
        <td>{{ $item['quantity'] }}</td>
        <td>£{{ number_format($item['price'], 2) }}</td>
        <td>£{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <p class="total">Total Amount: £{{ number_format($order->total_amount, 2) }}</p>

  <div class="footer">
    Thank you for your order. We hope to see you again soon! 🌌
  </div>

</body>
</html>
