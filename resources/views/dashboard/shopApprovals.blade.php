@extends('layouts.app')

@section('content')
<style>
    :root {
        --theme-color: #F97352;
        --theme-color-hover: #e65f40;
        --bg-soft: #fff9f7; /* Very light version of theme for backgrounds */
    }

    body {
        background-color: #fdfdfd;
    }

    /* Text Colors */
    .text-theme { color: var(--theme-color) !important; }

    /* Card Styling */
    .shop-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        position: relative;
    }

    .shop-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--theme-color);
        opacity: 0;
        transition: 0.3s;
    }

    .shop-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(249, 115, 82, 0.15);
    }

    .shop-card:hover::before {
        opacity: 1;
    }

    /* Image Styling */
    .img-wrapper {
        position: relative;
        height: 140px;
        overflow: hidden;
        margin: 15px 15px 0 15px;
        border-radius: 12px;
    }

    .shop-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .shop-card:hover .shop-img {
        transform: scale(1.05);
    }

    /* Badges */
    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.95);
        color: var(--theme-color);
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        backdrop-filter: blur(4px);
    }

    /* Button Styling */
    .btn-theme {
        background-color: var(--theme-color);
        border: 1px solid var(--theme-color);
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }

    .btn-theme:hover {
        background-color: var(--theme-color-hover);
        border-color: var(--theme-color-hover);
        color: #fff;
        box-shadow: 0 4px 12px rgba(249, 115, 82, 0.3);
    }

    .btn-outline-theme {
        background-color: transparent;
        border: 1px solid #eee;
        color: #888;
        font-weight: 500;
        border-radius: 8px;
        padding: 6px 16px;
    }

    .btn-outline-theme:hover {
        border-color: var(--theme-color);
        color: var(--theme-color);
        background: #fff;
    }
    
    .btn-link-muted {
        color: #999;
        text-decoration: none;
        font-size: 0.85rem;
        transition: 0.2s;
    }
    .btn-link-muted:hover {
        color: var(--theme-color);
    }

    /* Typography */
    .card-title { font-size: 1.15rem; color: #2d2d2d; }
    .card-text { color: #6c757d; }
    .info-icon { opacity: 0.7; margin-right: 5px; }

</style>

<div class="container py-5">
    
    <div class="d-flex align-items-center justify-content-between mb-5">
        <h3 class="fw-bold m-0" style="color: #333;">
            Shop Requests
        </h3>
        <span class="badge rounded-pill" style="background: var(--bg-soft); color: var(--theme-color);">
            {{ $pendingShops->count() }} Pending
        </span>
    </div>

    @if($pendingShops->isEmpty())
        <div class="text-center py-5">
            <div class="mb-3" style="font-size: 3rem; opacity: 0.2;">📂</div>
            <h5 class="text-muted">No pending requests</h5>
            <p class="small text-muted">New shop registrations will appear here.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($pendingShops as $shop)
                <div class="col-md-6 col-lg-4">
                    <div class="shop-card h-100 d-flex flex-column">

                        {{-- Image Area --}}
                        <div class="img-wrapper">
                            <img 
                                {{-- src="{{ asset('storage/' . $shop->profileImage) }}"  --}}
                                src="{{ $shop->profileImage }}" 
                                class="shop-img" 
                                alt="{{ $shop->name }}"
                            >
                            <span class="status-badge">
                                {{ strtoupper($shop->status) }}
                            </span>
                        </div>

                        {{-- Content Area --}}
                        <div class="p-4 flex-grow-1">
                            <h5 class="card-title fw-bold mb-2">{{ $shop->name }}</h5>
                            
                            <div class="d-flex align-items-center mb-2">
                                <span class="info-icon">📍</span>
                                <span class="text-muted small text-truncate">{{ $shop->address }}</span>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                                <span class="info-icon">📞</span>
                                <span class="text-muted small">{{ $shop->phone }}</span>
                            </div>
                        </div>

                        {{-- Action Area --}}
                        <div class="p-3 pt-0 mt-auto">
                            <form action="{{ route('shops.accept', $shop->id) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-theme w-100 py-2">
                                    Accept Request
                                </button>
                            </form>

                            <div class="row g-2">
                                <div class="col-6">
                                    <form action="{{ route('shops.decline', $shop->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-theme w-100 btn-sm">
                                            Decline
                                        </button>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <form action="{{ route('shops.suspend', $shop->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-theme w-100 btn-sm">
                                            Suspend
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection