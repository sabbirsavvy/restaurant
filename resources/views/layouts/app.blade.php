<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Website - @yield('title')</title>
    <!-- Include your CSS framework (e.g., Bootstrap or Tailwind) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <!-- Navigation Bar -->
        <nav>
            <ul>
                @if (Auth::check())
                    @if (Auth::user()->role === 'admin')
                        <!-- Admin Navigation -->
                        <li><a href="{{ url('/admin/dashboard') }}">Admin Dashboard</a></li>
                        <li><a href="{{ url('/admin/menu') }}">Manage Menu</a></li>
                        <li><a href="{{ url('/admin/reservations') }}">Manage Reservations</a></li>
                        <li><a href="{{ url('/admin/orders') }}">Manage Orders</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Logout</button>
                            </form>
                        </li>
                    @else
                        <!-- Normal User Navigation -->
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/menu') }}">Menu</a></li>
                        <li><a href="{{ url('/book-table') }}">Book Table</a></li>
                        <li><a href="{{ url('/order-online') }}">Order Online</a></li>
                        <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Logout</button>
                            </form>
                        </li>
                    @endif
                @else
                    <!-- Guest Navigation -->
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/about') }}">About Us</a></li>
                    <li><a href="{{ url('/menu') }}">Menu</a></li>
                    <li><a href="{{ url('/book-table') }}">Book Table</a></li>
                    <li><a href="{{ url('/order-online') }}">Order Online</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endif
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Restaurant. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
