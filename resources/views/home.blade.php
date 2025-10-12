@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="text-center text-white d-flex align-items-center justify-content-center flex-column"
                style="background: url('{{ asset('images/hero-bg.png') }}') center/cover no-repeat; height: 70vh;">
        <h1 style="font-family: 'Pacifico'; font-size: 3rem;">Book a Meal &<br>We'll Cook It!</h1>
    </section>

    {{-- Menu Section --}}
    <section class="text-center py-5" style="background-color: #F97352;">
        <div class="container">
            <h2 class="mb-5" style="font-family: 'Potta One'; color: #fff;">Check out our menu!</h2>

            <div id="menu" class="row g-4 justify-content-center">
                @foreach ($meals as $meal)
                    <div class="col-md-4 col-lg-3">
                        <div class="card shadow-sm border-0 h-100" style="border-radius: 20px;">
                            <a href="{{ route('menu.show', $meal->id) }}" class="text-decoration-none">
                                <img src="{{ asset($meal->image_url) }}" class="card-img-top" alt="{{ $meal->name }}" 
                                    style="border-top-left-radius: 20px; border-top-right-radius: 20px; height: 200px; object-fit: cover;">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <a href="{{ route('menu.show', $meal->id) }}" class="text-decoration-none">
                                    <h5 class="card-title mb-1" style="color: #1E293B;">{{ $meal->name }}</h5>
                                </a>
                                <p class="text-muted mb-2 flex-grow-1" style="font-size: 0.85rem;">{{ $meal->description }}</p>
                                <p class="text-muted mb-3 fw-bold">Rp. {{ number_format($meal->price, 0, ',', '.') }}</p>
                                <a href="{{ route('menu.show', $meal->id) }}" class="btn w-100 fw-bold text-decoration-none" 
                                    style="background-color: #F97352; color: white; border-radius: 15px; border: none; padding: 8px 0;">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">
                <a href="/menu" class="btn px-5 py-2 fw-bold"
                    style="background-color: #1E293B; color: white; border-radius: 30px; box-shadow: 0 4px #111;">
                    See More
                </a>
            </div>
        </div>
    </section>
@endsection