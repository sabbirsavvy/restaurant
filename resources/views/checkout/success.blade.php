@extends('layouts.app')

@section('title', 'Payment Success')

@section('content')
    <h1>Payment Successful</h1>
    <p>Your payment has been processed successfully. Thank you for your order!</p>
    <a href="{{ url('/') }}">Return to Home</a>
@endsection
