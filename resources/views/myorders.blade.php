@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="css/settings.css">

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

        .order-card {
            transition: all 0.2s;
        }

        .order-card:hover {
            background-color: #fcfcfc;
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
                <div class="card shadow-sm border-0 overflow-hidden" style="min-height: 600px;">

                    {{-- Header & Tabs --}}
                    <div class="card-header bg-white border-bottom pt-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0 fw-bold" style="color: #333;">My Orders</h4>
                        </div>

                        <ul class="nav nav-pills card-header-pills pb-3" id="orders-tab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active rounded-pill px-4 me-2" id="pills-all-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab">
                                    All Orders
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

                            {{-- 1. ALL ORDERS --}}
                            <div class="tab-pane fade show active" id="pills-all" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($allOrder as $order)
                                        @include('orders.order-item', ['order' => $order])
                                    @empty
                                        @include('orders.empty-state')
                                    @endforelse
                                </div>
                            </div>

                            {{-- 2. PENDING --}}
                            <div class="tab-pane fade" id="pills-pending" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($pendingOrder as $order)
                                        @include('orders.order-item', ['order' => $order])
                                    @empty
                                        @include('orders.empty-state')
                                    @endforelse
                                </div>
                            </div>

                            {{-- 3. COOKING (CONFIRMED) --}}
                            <div class="tab-pane fade" id="pills-cooking" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($confirmedOrder as $order)
                                        @include('orders.order-item', ['order' => $order])
                                    @empty
                                        @include('orders.empty-state')
                                    @endforelse
                                </div>
                            </div>

                            {{-- 4. READY --}}
                            <div class="tab-pane fade" id="pills-ready" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($readyOrder as $order)
                                        @include('orders.order-item', ['order' => $order])
                                    @empty
                                        @include('orders.empty-state')
                                    @endforelse
                                </div>
                            </div>

                            {{-- 5. COMPLETED --}}
                            <div class="tab-pane fade" id="pills-completed" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($completedOrder as $order)
                                        @include('orders.order-item', ['order' => $order])
                                    @empty
                                        @include('orders.empty-state')
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