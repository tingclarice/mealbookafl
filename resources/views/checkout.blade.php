@extends('layouts.app')

@section('head')
    {{-- Keep Midtrans Script --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    {{-- Reuse Cart CSS if needed, otherwise inline styles are provided below --}}
    <link rel="stylesheet" href="css/cart.css">
@endsection

@section('content')
    <div style="background-color: #fff9f8; min-height: 100vh; padding: 3rem 0;">
        <div class="container my-5">
            <div class="row g-4">

                {{-- Left Column: Order Details --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-2" style="border-radius: 1.5rem;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h4 class="fw-bold mb-0" style="color: #333;">
                                <i class="bi bi-receipt-cutoff me-2" style="color: #F97352;"></i>
                                Order Summary
                            </h4>
                        </div>

                        <div class="card-body px-4">
                            @foreach ($order->items as $item)
                                <div class="d-flex align-items-start mb-4 border-bottom pb-4 last:border-0">

                                    {{-- Placeholder for Image if you uncomment it later --}}
                                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center"
                                        style="width: 70px; height: 70px; flex-shrink: 0; background-color: #fff1ee !important;">
                                        <i class="bi bi-egg-fried" style="color: #F97352; font-size: 1.5rem;"></i>
                                    </div>

                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="fw-bold mb-1" style="font-size: 1.1rem;">{{ $item->meal_name }}</h6>
                                            <span class="badge rounded-pill px-3 py-2"
                                                style="background-color: #fff1ee; color: #F97352;">
                                                x{{ $item->quantity }}
                                            </span>
                                        </div>

                                        <p class="text-muted small mb-2">
                                            Unit Price: Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </p>

                                        {{-- Options List --}}
                                        @if ($item->options && $item->options->isNotEmpty())
                                            <div class="small text-muted p-2 rounded-3"
                                                style="background-color: #fcfcfc; border: 1px solid #f0f0f0;">
                                                @foreach ($item->options as $option)
                                                    <div class="d-flex justify-content-between">
                                                        <span>• {{ $option->option_name }}</span>
                                                        @if($option->price > 0)
                                                            <span class="fw-medium text-dark">
                                                                +Rp {{ number_format($option->price, 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Item Subtotal --}}
                                    <div class="ms-4 text-end">
                                        @php
                                            $itemTotal = $item->price * $item->quantity;
                                            foreach ($item->options as $opt) {
                                                $itemTotal += $opt->price * $item->quantity;
                                            }
                                        @endphp
                                        <span class="fw-bold" style="color: #F97352; font-size: 1.1rem;">
                                            Rp {{ number_format($itemTotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Right Column: Payment Action --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="border-radius: 1.5rem; top: 2rem;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Payment Details</h5>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Order ID</span>
                                <span class="fw-bold text-dark">#{{ $order->id }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total Items</span>
                                <span class="fw-bold">{{ $order->items->sum('quantity') }} items</span>
                            </div>

                            <hr style="border-top: 2px dashed #eee; background: none;">

                            <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                                <span class="fw-bold text-muted">Total Amount</span>
                                <span class="fw-bold fs-4" style="color: #F97352;">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- Pay Button --}}
                            <button id="pay-button" class="btn w-100 py-3 fw-bold text-white shadow-sm"
                                style="background-color: #F97352; border-radius: 12px; border: none; transition: 0.3s;">
                                <i class="bi bi-shield-check me-2"></i> Pay Now
                            </button>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script remains unchanged regarding logic --}}
    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            snap.pay("{{ $order->snap_token }}", {
                onSuccess: function (result) {
                    window.location.href = "/myOrders";
                },
                onPending: function (result) {
                    alert('Waiting for payment');
                },
                onError: function (result) {
                    alert('Payment failed');
                },
                onClose: function () {
                    // alert('You closed the popup without finishing the payment');
                }
            });
        });
    </script>
@endsection