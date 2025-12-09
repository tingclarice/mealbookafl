@extends('layouts.app')

@section('content')
<div style="background-color: #FEF3F0; min-height: 100vh;">
    
    {{-- Back Button --}}
    <div class="container pt-4">
        <a href="{{ route('menu') }}" class="btn d-inline-flex align-items-center"
            style="background: none; border: none; color: #F97352; font-size: 1.1rem; padding: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6" />
            </svg>
            <span class="ms-2">Back</span>
        </a>
    </div>

    {{-- Hero Section with Shop Image --}}
    <section class="position-relative text-white mt-3" style="height: 300px; overflow: hidden; border-radius: 0;">
        <div style="background: linear-gradient(rgba(249, 115, 82, 0.8), rgba(249, 115, 82, 0.8)), 
                    url('{{ $shop->profileImage ? asset('storage/' . $shop->profileImage) : asset('images/hero-bg.webp') }}') center/cover no-repeat;
                    height: 100%; width: 100%;"></div>
        
        <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
            <div class="container">
                <h1 class="fw-bold mb-2" style="font-family: 'Potta One'; font-size: 2.5rem;">{{ $shop->name }}</h1>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="badge {{ $shop->status === 'OPEN' ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                        {{ $shop->status }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- Shop Details --}}
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                
                {{-- Left Column: Shop Info --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4" style="color: #1E293B;">
                                <i class="bi bi-shop me-2" style="color: #F97352;"></i>
                                Shop Information
                            </h5>
                            
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt-fill me-3 fs-5" style="color: #F97352;"></i>
                                    <div>
                                        <small class="text-muted d-block mb-1">Address</small>
                                        <span style="color: #1E293B;">{{ $shop->address }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-telephone-fill me-3 fs-5" style="color: #F97352;"></i>
                                    <div>
                                        <small class="text-muted d-block mb-1">Phone</small>
                                        <span style="color: #1E293B;">{{ $shop->phone }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-0">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle-fill me-3 fs-5" style="color: #F97352;"></i>
                                    <div>
                                        <small class="text-muted d-block mb-1">About</small>
                                        <p class="mb-0" style="color: #1E293B; line-height: 1.6;">{{ $shop->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Menu Items --}}
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0" style="color: #1E293B;">Our Menu</h4>
                        <span class="text-muted">{{ $shop->meals->count() }} items</span>
                    </div>
                    
                    @if($shop->meals->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3" style="font-size: 3rem; opacity: 0.2;">🍽️</div>
                            <h5 class="text-muted">No menu items yet</h5>
                            <p class="small text-muted">Check back later for delicious offerings!</p>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($shop->meals as $meal)
                                <div class="col-md-6 col-xl-4">
                                    <x-meal-card :meal="$meal" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                
            </div>
        </div>
    </section>
</div>
@endsection