<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MealBook</title>

    {{-- Bootstrap (local file version) --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>


    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @yield('head')

</head>

<body class="d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    @include('layouts.navbar', ["user" => Auth::user()])

    {{-- Page Content --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Bootstrap JS --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    {{-- QR Code JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    @stack('scripts')

    <script>
        (function () {
            if (!sessionStorage.getItem('tz_sent')) {
                fetch('/timezone', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
                    })
                }).then(() => {
                    sessionStorage.setItem('tz_sent', '1');
                });
            }
        })();
    </script>
</body>

</html>