<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MealBook</title>

    {{-- Bootstrap (local file version) --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @yield('head')

</head>
<body class="d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Page Content --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Bootstrap JS --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
