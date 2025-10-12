@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="text-center text-white d-flex align-items-center justify-content-center flex-column"
                style="background: url('{{ asset('images/hero-bg.png') }}') center/cover no-repeat; height: 70vh;">
        <h1 style="font-family: 'Pacifico'; font-size: 3rem;">Book a Meal &<br>We’ll Cook It!</h1>
    </section>

    {{-- Menu Section --}}
    <section class="text-center py-5" style="background-color: #F97352;">
        <div class="container">
            <h2 class="mb-5" style="font-family: 'Potta One'; color: #fff;">Check out our menu!</h2>

            <div id="menu" class="row g-4 justify-content-center">
                @foreach ($meals as $meal)
                    <div class="col-md-4 col-lg-3">
                        <div class="card shadow-sm border-0" style="border-radius: 20px;">
                            <img src="{{ asset($meal->image_url) }}" class="card-img-top" alt="{{ $meal->name }}" 
                                style="border-top-left-radius: 20px; border-top-right-radius: 20px; height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title mb-1">{{ $meal->name }}</h5>
                                <p class="text-muted mb-2" style="font-size: 0.85rem;">{{ $meal->description }}</p>
                                <p class="text-muted mb-0 fw-bold">Rp. {{ number_format($meal->price, 0, ',', '.') }}</p>
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
