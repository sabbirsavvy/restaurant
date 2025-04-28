

@section('title', 'Payment Cancelled')

<style>
    .cancel-container {
        max-width: 700px;
        margin: 100px auto 40px;
        background-color: #1a1a1a;
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 40px;
        border-radius: 12px;
        text-align: center;
        color: #d1d1d1;
        box-shadow: 0 0 20px rgba(138, 180, 248, 0.15);
    }

    .cancel-container h1 {
        font-size: 2.5rem;
        font-family: 'Orbitron', sans-serif;
        color: #e74c3c;
        margin-bottom: 20px;
        text-shadow: 0 0 10px rgba(255, 87, 87, 0.4);
    }

    .cancel-container p {
        font-size: 1.1rem;
        color: #ccc;
        margin-bottom: 30px;
    }

    .cancel-container a {
        display: inline-block;
        background: linear-gradient(45deg, #8ab4f8, #5f8ee2);
        color: black;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: bold;
        text-transform: uppercase;
        transition: 0.3s;
        text-decoration: none;
    }

    .cancel-container a:hover {
        background: linear-gradient(45deg, #5f8ee2, #1c4fd6);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(138, 180, 248, 0.4);
    }

    @media (max-width: 600px) {
        .cancel-container {
            margin: 120px 1rem 40px;
            padding: 30px 20px;
        }
    }
</style>

<div class="cancel-container">
    <h1>Payment Cancelled</h1>
    <p>Your payment was cancelled. Please try again or contact support if you need assistance.</p>
    <a href="{{ route('cart.show') }}"><i class="fas fa-shopping-cart mr-2"></i>Return to Cart</a>
</div>

