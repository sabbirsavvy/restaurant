<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reservation Pending – Dune Restaurant</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  <style>
    body {
      background-color: #0a0a0a;
      color: #d1d1d1;
      font-family: 'Roboto Mono', monospace;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      justify-content: center;
      align-items: center;
      padding: 2rem;
      text-align: center;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(ellipse at center, rgba(138, 180, 248, 0.05), transparent 70%),
                  radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.04), transparent 40%);
      z-index: -1;
    }

    h1 {
      font-family: 'Orbitron', sans-serif;
      font-size: 2.5rem;
      color: #8ab4f8;
      margin-bottom: 1rem;
    }

    p {
      font-size: 1.1rem;
      max-width: 600px;
      margin: 0 auto 1.5rem;
      color: #ccc;
    }

    .waiting-glow {
      font-size: 1rem;
      color: #FFD700;
      animation: pulse 1.5s infinite alternate;
    }

    @keyframes pulse {
      from { opacity: 0.6; }
      to { opacity: 1; }
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
      box-shadow: 0 0 10px rgba(138, 180, 248, 0.4);
    }

    .countdown {
      font-size: 2rem;
      margin: 1.5rem 0;
      font-family: 'Orbitron', sans-serif;
      color: #8ab4f8;
    }

    .status-message {
      margin-top: 2rem;
      font-size: 1.1rem;
      color: #f87171;
    }

    .status-message.success {
      color: #00ff88;
    }
  </style>
</head>
<body>

<h1 id="pageTitle">⏳ Reservation Pending</h1>
<p id="subText">
  Thank you for booking a table at <strong>Restaurant</strong>. Your reservation is being reviewed.
</p>
  <div class="countdown" id="countdown">05:00</div>
  <p class="waiting-glow" id="glowMsg">Please wait while we prepare your stellar experience...</p>
  <p class="status-message" id="statusMessage"></p>

  <a href="{{ url('/') }}" class="gold-btn mt-6" id="homeBtn" style="display: none;">Return Home</a>
 
  <script>
  const countdownEl = document.getElementById('countdown');
  const messageEl = document.getElementById('statusMessage');
  const homeBtn = document.getElementById('homeBtn');
  const reservationId = '{{ session('reservation_id') }}';

  let timeLeft = 5 * 60;
  let timerInterval = null;
  let statusInterval = null;

  function updateCountdown() {
    const minutes = String(Math.floor(timeLeft / 60)).padStart(2, '0');
    const seconds = String(timeLeft % 60).padStart(2, '0');
    countdownEl.textContent = `${minutes}:${seconds}`;

    if (timeLeft <= 0) {
      clearInterval(timerInterval);
      clearInterval(statusInterval);
      cancelReservation();
    }

    timeLeft--;
  }

  function cancelReservation() {
    fetch(`/reservations/${reservationId}/cancel`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    }).then(() => {
      countdownEl.textContent = "00:00";
      messageEl.classList.remove('success');
      messageEl.innerHTML = `⚠️ Looks like we're a bit busy right now. Please try again later or call us at <strong>01234 567890</strong>.`;
      homeBtn.style.display = 'inline-block';
    });
  }

  function handleSuccess(date, time) {
    clearInterval(timerInterval);
    clearInterval(statusInterval);
    countdownEl.textContent = '';
    messageEl.classList.add('success');
    messageEl.innerHTML = `🎉 See you on <strong>${date}</strong> at <strong>${time}</strong>!`;
    homeBtn.style.display = 'inline-block';
  }

  function handleDeclined() {
    clearInterval(timerInterval);
    clearInterval(statusInterval);
    countdownEl.textContent = "00:00";
    messageEl.classList.remove('success');
    messageEl.innerHTML = `❌ Unfortunately your reservation was not accepted. Please try again later or call us at <strong>01234 567890</strong>.`;
    homeBtn.style.display = 'inline-block';
  }

  function checkStatus() {
    fetch(`/reservations/${reservationId}/status`)
      .then(res => res.json())
      .then(data => {
        console.log('[Reservation status]', data);

        if (data.status === 'accepted') {
  clearInterval(timerInterval);
  clearInterval(statusInterval);

  countdownEl.style.display = 'none';
  const glowMsg = document.getElementById('glowMsg');
  if (glowMsg) glowMsg.style.display = 'none';

  document.getElementById('pageTitle').innerHTML = '✅ Reservation Confirmed';
  document.getElementById('subText').style.display = 'none';

  const reservationDate = new Date(data.date + 'T' + data.time); // full datetime
  const now = new Date();

  const diffDays = Math.floor((reservationDate - new Date(now.getFullYear(), now.getMonth(), now.getDate())) / (1000 * 60 * 60 * 24));
  const hour = reservationDate.getHours();

  let timeLabel = '';
  if (hour < 17) {
    timeLabel = diffDays === 0 ? 'this afternoon' : diffDays === 1 ? 'tomorrow afternoon' : `on <strong>${data.date}</strong>`;
  } else {
    timeLabel = diffDays === 0 ? 'this evening' : diffDays === 1 ? 'tomorrow evening' : `on <strong>${data.date}</strong>`;
  }

  const timeStr = `<strong>${data.time}</strong>`;
  const message = timeLabel.startsWith('on') ?
    `🎉 Your table is reserved. <br>See you ${timeLabel} at ${timeStr}!` :
    `🎉 Your table is reserved. <br>See you ${timeLabel} at ${timeStr}!`;

  messageEl.classList.add('success');
  messageEl.innerHTML = message;
  homeBtn.style.display = 'inline-block';
}

else if (data.status === 'declined') {
  clearInterval(timerInterval);
  clearInterval(statusInterval);

  // Hide countdown and glow
  countdownEl.style.display = 'none';
  const glowMsg = document.getElementById('glowMsg');
  if (glowMsg) glowMsg.style.display = 'none';

  // Change heading and subtext
  document.getElementById('pageTitle').innerHTML = '❌ Reservation Declined';
  document.getElementById('subText').style.display = 'none';

  // Show error message
  messageEl.classList.remove('success');
  messageEl.innerHTML = `❌ Unfortunately, your reservation could not be confirmed.<br>Please try again later or call us at <strong>01234 567890</strong>.`;
  homeBtn.style.display = 'inline-block';
}

      }).catch(err => {
        console.error('Status check error:', err);
      });
  }

  if (reservationId) {
    timerInterval = setInterval(updateCountdown, 1000);
    statusInterval = setInterval(checkStatus, 3000);
  }
</script>



</body>
</html>
