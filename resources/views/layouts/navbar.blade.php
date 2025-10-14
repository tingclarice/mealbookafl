<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #F97352;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/" style="font-family: 'Pacifico'; color: #fff;">MealBook</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center gap-3">
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('home') }}">home</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('about') }}">about</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('home') }}#menu">menu</a></li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('cart') }}">
                    <i class="bi bi-cart fs-4"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="">
                    <i class="bi bi-person-circle fs-4"></i>
                </a>
            </li>

            {{-- Login Button --}}
            {{-- <li class="nav-item">
                <a class="btn btn-dark px-4 py-2 rounded-pill" href="/login">Log In</a>
            </li> --}}
        </ul>
        </div>
    </div>
</nav>
