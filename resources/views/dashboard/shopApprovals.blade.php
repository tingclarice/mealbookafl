@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="/css/shopApprovals.css">

    <div class="container py-5">

        {{-- Header + Filter Buttons --}}
        <div class="d-flex align-items-center justify-content-between mb-5">
            <h3 class="fw-bold m-0" style="color: #333;">
                Shop Requests
            </h3>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.shopApprovals', ['filter' => 'pending']) }}"
                    class="btn btn-sm {{ request('filter', 'pending') === 'pending' ? 'btn-theme' : 'btn-outline-theme' }}">
                    Pending ({{ $pendingShops->count() }})
                </a>
                <a href="{{ route('admin.shopApprovals', ['filter' => 'active']) }}"
                    class="btn btn-sm {{ request('filter') === 'active' ? 'btn-theme' : 'btn-outline-theme' }}">
                    Active ({{ $activeShops->count() }})
                </a>
                <a href="{{ route('admin.shopApprovals', ['filter' => 'rejected']) }}"
                    class="btn btn-sm {{ request('filter') === 'rejected' ? 'btn-theme' : 'btn-outline-theme' }}">
                    Rejected ({{ $rejectedShops->count() }})
                </a>
                <a href="{{ route('admin.shopApprovals', ['filter' => 'suspended']) }}"
                    class="btn btn-sm {{ request('filter') === 'suspended' ? 'btn-theme' : 'btn-outline-theme' }}">
                    Suspended ({{ $suspendedShops->count() }})
                </a>
            </div>
        </div>

        @php
            $filter = request('filter', 'pending');
            $shopsToShow = match ($filter) {
                'active' => $activeShops,
                'rejected' => $rejectedShops,
                'suspended' => $suspendedShops,
                default => $pendingShops,
            };
        @endphp

        @if($shopsToShow->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3" style="font-size: 3rem; opacity: 0.2;">
                    <i class="bi bi-shop me-2"></i>
                </div>
                <h5 class="text-muted">No {{ ucfirst($filter) }} shops</h5>
                <p class="small text-muted">Shop registrations will appear here.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($shopsToShow as $shop)
                    <div class="col-md-6 col-lg-4">
                        <div class="shop-card h-100 d-flex flex-column">

                            {{-- Image Area --}}
                            <div class="img-wrapper">
                                <img src="{{ asset('storage/' . $shop->profileImage) }}" class="shop-img" alt="{{ $shop->name }}">
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

                            {{-- Action Area (buttons are functional as before) --}}
                            <div class="p-3 pt-0 mt-auto">
                                <form action="{{ route('shops.accept', $shop->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" {{ $shop->status == "OPEN" || $shop->status == "CLOSE" ? 'disabled' : '' }}
                                        class="btn btn-theme w-100 py-2">
                                        Accept Request
                                    </button>
                                </form>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <form action="{{ route('shops.decline', $shop->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" {{ $shop->status == "REJECTED" || $shop->status == "OPEN" || $shop->status == "CLOSE" ? 'disabled' : '' }}
                                                class="btn btn-outline-theme w-100 btn-sm">
                                                Decline
                                            </button>
                                        </form>
                                    </div>

                                    <div class="col-6">
                                        <form action="{{ route('shops.suspend', $shop->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-theme w-100 btn-sm" {{ $shop->status == "REJECTED" || $shop->status == "SUSPENDED" ? 'disabled' : '' }}>
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