<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #F97352;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/" style="font-family: 'Pacifico'; color: #fff;">MealBook</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center gap-3">
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('home') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('about') }}">About</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('menu') }}">Menu</a></li>
            
            {{-- If Login and not --}}
            @if (Auth::check())
                
                {{-- Dashboard button (if admin) --}}
                @if(Auth::user()->role === "ADMIN")
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a></li>
                @endif

                {{-- Logout Button --}}
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <li class="nav-item"><button class="nav-link text-white" type="submit">Logout</button></li>
                </form>

                {{-- Cart Button --}}
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('cart') }}">
                        <i class="bi bi-cart fs-4"></i>
                    </a>
                </li>

                {{-- Profile Button --}}
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">
                        <i class="bi bi-person-circle fs-4"></i>
                    </a>
                </li>
            @else
                {{-- Login Button --}}
                <li class="nav-item">
                    <a class="btn btn-dark px-4 py-2 rounded-pill" href="{{ route('login') }}">
                        Log In
                    </a>
                </li>
            @endif

        </ul>
        </div>
    </div>
</nav>
