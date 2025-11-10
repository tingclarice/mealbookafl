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
    
    {{-- Simple navbar for auth pages (no user dropdown) --}}
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #F97352;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/" style="font-family: 'Pacifico'; color: #fff;">MealBook</a>
        </div>
    </nav>

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