<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Processing Your Order - Dune Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto+Mono&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      background-color: #0a0a0a;
      color: #d1d1d1;
      font-family: 'Roboto Mono', monospace;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 2rem;
      text-align: center;
      position: relative;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(ellipse at center, rgba(58, 123, 213, 0.1), transparent 70%),
                  radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.03), transparent 40%);
      z-index: -1;
    }

    h1 {
      font-size: 2rem;
      font-family: 'Orbitron', sans-serif;
      color: #8ab4f8;
      margin-bottom: 1rem;
    }

    p {
      font-size: 1.1rem;
      color: #bbb;
      margin-bottom: 2rem;
    }

    #loading img {
      width: 80px;
      height: 80px;
    }

    #result-message {
      font-size: 1.5rem;
      font-weight: bold;
      color: #FFD700;
      margin-bottom: 1.5rem;
    }

    a {
      background: linear-gradient(45deg, #8ab4f8, #5f8ee2);
      color: black;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s;
    }

    a:hover {
      background: linear-gradient(45deg, #5f8ee2, #1c4fd6);
      color: white;
    }

    .spinner-container {
  display: flex;
  justify-content: center;
  margin: 30px auto;
}

.interstellar-spinner {
  width: 50px;
  height: 50px;
  border: 5px solid rgba(138, 180, 248, 0.2);
  border-top: 5px solid #8ab4f8;
  border-radius: 50%;
  animation: spin 1.2s linear infinite;
  box-shadow: 0 0 20px #8ab4f8;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

  </style>
</head>
<body>

<div id="processing-header">
  <h1>Your order is being processed...</h1>
  <p>Please wait while we process your order.</p>
</div>

<p id="countdown-timer" style="color: #8ab4f8; font-weight: bold;">⏳ Time left: 3:00</p>


  <!-- Loading Spinner -->
  <div id="loading" class="spinner-container">
  <div class="interstellar-spinner"></div>
</div>


  <!-- Status Message -->
  <div id="order-result" style="display:none;">
  <h2 id="result-message" class="text-yellow-400 font-bold text-xl"></h2>

  <!-- Order Info -->
  <div id="order-info">
    @if($order->type === 'collection')
      <p id="order-time" class="text-yellow-300 mt-2">
        🕓 Your food will be ready to collect at <strong>{{ $collectionTime->format('g:i A') }}</strong>.
      </p>
    @elseif($order->type === 'delivery')
      <p id="order-time" class="text-yellow-300 mt-2">
        🚗 Your food will be delivered by <strong>{{ $deliveryTime->format('g:i A') }}</strong>.
      </p>
    @endif
  </div>

  <!-- Extra Declined Note -->
  <p id="declined-extra" style="color: #ff4c4c; display: none; font-weight: bold;">
    📞 Please call us at <strong>01234 567890</strong> to get more details.
  </p>

  <a href="{{ route('dashboard') }}" class="mt-4 inline-block">Return to Dashboard</a>
</div>



<script>
  const orderId = {{ $order->id }};
  const loading = document.getElementById('loading');
  const resultBox = document.getElementById('order-result');
  const resultMessage = document.getElementById('result-message');
  const header = document.getElementById('processing-header');
  const orderTime = document.getElementById('order-time');
  const declinedExtra = document.getElementById('declined-extra');
  const countdownDisplay = document.getElementById('countdown-timer');

  let remainingSeconds = 180; // 3 minutes
  let processingResolved = false;

  function updateCountdown() {
    const minutes = Math.floor(remainingSeconds / 60);
    const seconds = remainingSeconds % 60;
    countdownDisplay.innerText = `⏳ Time left: ${minutes}:${seconds.toString().padStart(2, '0')}`;
  }

  const countdownInterval = setInterval(() => {
    if (processingResolved) return;
    remainingSeconds--;
    updateCountdown();

    if (remainingSeconds <= 0) {
      clearInterval(statusInterval);
      handleDecline();
    }
  }, 1000);

  function handleDecline() {
    processingResolved = true;
    loading.style.display = 'none';
    header.style.display = 'none';
    countdownDisplay.style.display = 'none';
    orderTime.style.display = 'none';
    declinedExtra.style.display = 'block';
    resultMessage.innerText = '⚠️ Your order was declined due to no response.';
    resultMessage.style.color = '#ff4c4c';
    resultBox.style.display = 'block';
  }

  const statusInterval = setInterval(() => {
    fetch("{{ url('/order/status') }}/" + orderId)
      .then(res => res.json())
      .then(data => {
        if (processingResolved) return;

        if (data.status !== 'pending') {
          processingResolved = true;
          clearInterval(statusInterval);
          clearInterval(countdownInterval);

          loading.style.display = 'none';
          header.style.display = 'none';
          countdownDisplay.style.display = 'none';

          let message = '';

          if (data.status === 'accepted') {
            message = '🌟 Your order has been accepted!';
          } else if (data.status === 'declined') {
            message = '⚠️ Your order was declined.';
            resultMessage.style.color = '#ff4c4c';
            orderTime.style.display = 'none';
            declinedExtra.style.display = 'block';
          }

          resultMessage.innerText = message;
          resultBox.style.display = 'block';
        }
      })
      .catch(err => console.error('Error:', err));
  }, 5000);

  updateCountdown(); // initialize timer display
</script>



</body>
</html>
