@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="css/shopApprovals.css">

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
                                src="{{ asset('storage/' . $shop->profileImage) }}" 
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