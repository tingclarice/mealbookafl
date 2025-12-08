@extends('layouts.app')

@section('content')
{{-- Hero Section --}}
<section class="text-center text-white d-flex align-items-center justify-content-center"
    style="background: linear-gradient(135deg, #F97352 0%, #ff8c6b 100%); min-height: 30vh;">
    <div>
        <h1 class="mb-2" style="font-family: 'Potta One', sans-serif; font-size: 2.5rem;">Shop Dashboard</h1>
        <p class="lead">Manage your shop and orders</p>
    </div>
</section>

<div class="container py-5" style="min-height: 60vh;">
    @foreach($shops as $shop)
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
            <div class="card-header d-flex justify-content-between align-items-center" 
                 style="background-color: #F97352; color: white; padding: 1.5rem; border-radius: 20px 20px 0 0;">
                <div class="d-flex align-items-center">
                    @if($shop->profileImage)
                        <img src="{{ asset('storage/' . $shop->profileImage) }}" 
                             alt="{{ $shop->name }}" 
                             class="rounded-circle me-3"
                             style="width: 50px; height: 50px; object-fit: cover; border: 2px solid white;">
                    @else
                        <div class="rounded-circle bg-white text-dark d-flex align-items-center justify-content-center me-3"
                             style="width: 50px; height: 50px; font-weight: bold;">
                            {{ strtoupper(substr($shop->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h4 class="mb-0">{{ $shop->name }}</h4>
                        <small class="opacity-75">
                            Role: {{ $shop->pivot->role }}
                        </small>
                    </div>
                </div>
                <div>
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="bi bi-wallet2 me-1"></i>
                        Balance: Rp {{ number_format($shop->wallet->balance ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row g-4 mb-4">
                    {{-- Shop Info --}}
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-info-circle me-2"></i>Shop Information
                            </h6>
                            <p class="mb-2">
                                <strong>Address:</strong><br>
                                {{ $shop->address }}
                            </p>
                            <p class="mb-0">
                                <strong>Phone:</strong> {{ $shop->phone }}
                            </p>
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-graph-up me-2"></i>Quick Stats
                            </h6>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary mb-0">{{ $shop->meals->count() }}</h4>
                                    <small class="text-muted">Menu Items</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success mb-0">0</h4>
                                    <small class="text-muted">Orders Today</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-3 flex-wrap">
                    @if($shop->pivot->role === 'OWNER')
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-menu-button-wide me-2"></i>Manage Menu
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-gear me-2"></i>Shop Settings
                        </a>
                    @endif
                    <button class="btn btn-outline-primary" disabled>
                        <i class="bi bi-receipt me-2"></i>View Orders (Coming Soon)
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection