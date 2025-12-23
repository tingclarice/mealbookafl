@extends('layouts.app')

@section('content')
    {{-- Keep the same CSS for consistency --}}
    <style>
        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            background-color: #F97352 !important;
            color: white !important;
        }

        .nav-pills .nav-link {
            color: #555;
            font-weight: 500;
        }

        .nav-pills .nav-link:hover {
            background-color: #fff9f7;
            color: #F97352;
        }

        .shop-order-card {
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }

        .shop-order-card:hover {
            background-color: #fcfcfc;
            border-left: 4px solid #F97352;
        }

        /* Custom button style to match theme */
        .btn-theme {
            background-color: #F97352;
            color: white;
            border: none;
        }

        .btn-theme:hover {
            background-color: #e06546;
            color: white;
        }
    </style>

    <div class="container py-5">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Shop Dashboard Header --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        @if($shop->profileImage)
                            <img src="{{ asset('storage/' . $shop->profileImage) }}" class="rounded-circle me-3" width="60"
                                height="60" style="object-fit: cover; border: 2px solid #F97352;">
                        @else
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold"
                                style="width: 60px; height: 60px; background-color: #ccc; font-size: 1.5rem;">
                                {{ substr($shop->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="mb-0 fw-bold">{{ $shop->name }}</h4>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge {{ $shop->status === 'OPEN' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $shop->status }}
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-wallet2"></i> Balance:
                                    Rp {{ number_format($shop->wallet->balance ?? 0, 0, ',', '.') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- THE REQUESTED SINGLE BUTTON --}}
                    <div>
                        <button class="btn btn-theme rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                            <i class="bi bi-qr-code-scan"></i>
                            <span>Update Status (Scan)</span>
                        </button>
                    </div>
                </div>

                {{-- Main Card --}}
                <div class="card shadow-sm border-0 overflow-hidden" style="min-height: 600px;">

                    {{-- Tabs --}}
                    <div class="card-header bg-white border-bottom pt-4 pb-0">
                        <ul class="nav nav-pills card-header-pills pb-3" id="orders-tab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active rounded-pill px-4 me-2" id="pills-all-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab">
                                    Incoming
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill px-4 me-2" id="pills-pending-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-pending" type="button" role="tab">
                                    Pending
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill px-4 me-2" id="pills-cooking-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-cooking" type="button" role="tab">
                                    <i class="bi bi-fire me-1"></i> Cooking
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill px-4 me-2" id="pills-ready-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-ready" type="button" role="tab">
                                    <i class="bi bi-bell me-1"></i> Ready
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill px-4" id="pills-completed-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-completed" type="button" role="tab">
                                    Completed
                                </button>
                            </li>
                        </ul>
                    </div>

                    {{-- Body Content --}}
                    <div class="card-body bg-light p-0">
                        <div class="tab-content" id="pills-tabContent">

                            {{-- 1. INCOMING (Awaiting Payment) --}}
                            <div class="tab-pane fade show active" id="pills-all" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($pendingPayOrder as $order)
                                        @include('shopOrders.order-card-staff', ['order' => $order])
                                    @empty
                                        <div class="text-center py-5">
                                            <p class="text-muted">No incoming orders.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 2. PENDING (Paid, Awaiting Confirmation) --}}
                            <div class="tab-pane fade" id="pills-pending" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($pendingOrder as $order)
                                        @include('shopOrders.order-card-staff', ['order' => $order])
                                    @empty
                                        <div class="text-center py-5">
                                            <p class="text-muted">No pending orders.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 3. COOKING --}}
                            <div class="tab-pane fade" id="pills-cooking" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($confirmedOrder as $order)
                                        @include('shopOrders.order-card-staff', ['order' => $order])
                                    @empty
                                        <div class="text-center py-5">
                                            <p class="text-muted">Kitchen is clear.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 4. READY --}}
                            <div class="tab-pane fade" id="pills-ready" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($readyOrder as $order)
                                        @include('shopOrders.order-card-staff', ['order' => $order])
                                    @empty
                                        <div class="text-center py-5">
                                            <p class="text-muted">No orders waiting for pickup.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 5. COMPLETED --}}
                            <div class="tab-pane fade" id="pills-completed" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($completedOrder as $order)
                                        @include('shopOrders.order-card-staff', ['order' => $order])
                                    @empty
                                        <div class="text-center py-5">
                                            <p class="text-muted">No history yet.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection