<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>MealBook - @yield('title', 'Authentication')</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
</head>
<body class="d-flex flex-column min-vh-100">
    
    {{-- Navbar Component --}}
    @include('layouts.navbar')

    {{-- Page Content --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Bootstrap JS --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>