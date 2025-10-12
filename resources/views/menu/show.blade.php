@extends('layouts.app')

@section('content')
    {{-- Meal Detail Section --}}
    <section class="py-5" style="background-color: #FEF3F0; min-height: 100vh;">
        <div class="container">
            {{-- Back Button --}}
            <a href="{{ route('home') }}#menu" class="btn mb-4" style="background-color: transparent; border: none; font-size: 1.5rem; color: #1E293B;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
            </a>

            {{-- Meal Info --}}
            <div class="text-center mb-5">
                <h1 class="mb-2" style="font-family: 'Potta One'; color: #1E293B; font-size: 2.5rem;">{{ $meal->name }}</h1>
                <h3 style="color: #1E293B;">Rp. {{ number_format($meal->price, 0, ',', '.') }}</h3>
            </div>

            <div class="row g-5">
                {{-- Image Section --}}
                <div class="col-lg-5">
                    <img src="{{ asset($meal->image_url) }}" alt="{{ $meal->name }}" 
                            class="img-fluid shadow" 
                            style="border-radius: 30px; width: 100%; height: 400px; object-fit: cover;">
                </div>

                {{-- Description & Add to Cart --}}
                <div class="col-lg-7">
                    <div class="bg-white p-4 rounded shadow-sm" style="border-radius: 20px;">
                        <h4 class="mb-3" style="font-family: 'Potta One'; color: #1E293B;">Deskripsi</h4>
                        <p style="color: #64748B; line-height: 1.8; text-align: justify;">
                            {{ $meal->description }}
                        </p>
                        
                        <div class="mt-4">
                            <button class="btn px-5 py-3 fw-bold" 
                                    style="background-color: #2D1B4E; color: white; border-radius: 30px; border: none; font-size: 1.1rem;">
                                Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reviews Section --}}
            <div class="mt-5">
                <h3 class="mb-4" style="font-family: 'Potta One'; color: #1E293B;">Reviews</h3>
                
                <div class="row g-4">
                    @forelse($meal->reviews as $review)
                        <div class="col-12">
                            <div class="bg-white p-4 rounded shadow-sm" style="border-radius: 15px;">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                            style="width: 40px; height: 40px; background-color: #F97352 !important;">
                                        <span class="text-white fw-bold">{{ substr($review->user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0" style="color: #1E293B;">{{ $review->user->name }}</h6>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <p class="mb-0" style="color: #64748B;">{{ $review->message }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="bg-white p-4 rounded shadow-sm text-center" style="border-radius: 15px;">
                                <p class="mb-0 text-muted">Belum ada review untuk makanan ini.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Suggested Section --}}
            <div class="mt-5">
                <h3 class="mb-4" style="font-family: 'Potta One'; color: #1E293B;">Rekomendasi untuk Kamu</h3>
                
                <div class="row g-4">
                    @foreach($suggestedMeals as $suggested)
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ route('menu.show', $suggested->id) }}" class="text-decoration-none">
                                <div class="card shadow-sm border-0 h-100" style="border-radius: 20px;">
                                    <img src="{{ asset($suggested->image_url) }}" class="card-img-top" alt="{{ $suggested->name }}" 
                                        style="border-top-left-radius: 20px; border-top-right-radius: 20px; height: 150px; object-fit: cover;">
                                    <div class="card-body text-center">
                                        <h6 class="card-title mb-1" style="color: #1E293B;">{{ $suggested->name }}</h6>
                                        <p class="text-muted mb-0 small">Rp. {{ number_format($suggested->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection