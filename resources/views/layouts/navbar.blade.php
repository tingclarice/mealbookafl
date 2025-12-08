<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #F97352;">
    <div class="container">
        {{-- Brand --}}
        <a class="navbar-brand fw-bold" href="/" style="font-family: 'Pacifico'; color: #fff; font-size: 1.6rem;">
            MealBook
        </a>

        {{-- Hamburger button --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Nav links --}}
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center gap-lg-4 gap-2">
                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('menu') }}">Menu</a></li>

                @auth

                    <li class="nav-item">
                        <a class="nav-link text-white position-relative" href="{{ route('cart') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="rounded-circle me-2 border border-light" width="34" height="34" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-2" style="width:34px; height:34px;">
                                    <i class="bi bi-person-fill text-secondary"></i>
                                </div>
                            @endif
                            <span class="fw-semibold">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            {{-- <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-circle me-2"></i>Profile</a></li> --}}
                            
                            {{-- Settings --}}
                            @if(Auth::user()->isAdmin())
                                <a class="dropdown-item" href="{{ route('admin.shopApprovals') }}">
                                    <i class="bi bi-check-circle me-2"></i> Shop Approvals
                                </a>
                            @endif
                            
                            
                            @if(Auth::user()->isOwnerOrStaff())
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="bi bi-layout-text-sidebar-reverse me-2"></i> Shop Dashboard
                                </a>
                            @endif
                            

                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-gear me-2"></i> Settings
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- Auth buttons (balanced size & tone) --}}
                    <li class="nav-item">
                        <a class="btn btn-outline-light px-4 py-2 rounded-pill fw-semibold me-2"
                            style="transition: all 0.2s;"
                            href="{{ route('login') }}">
                            Log In
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn fw-semibold px-4 py-2 rounded-pill"
                            style="background-color: #fff; color: #F97352; transition: all 0.2s;"
                            href="{{ route('register') }}"
                            onmouseover="this.style.backgroundColor='#ffe4dd';"
                            onmouseout="this.style.backgroundColor='#fff';">
                            Register
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
