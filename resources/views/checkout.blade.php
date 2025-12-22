@extends('layouts.app')

@section('head')
    {{-- Keep Midtrans Script --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    {{-- Reuse Cart CSS if needed, otherwise inline styles are provided below --}}
    <link rel="stylesheet" href="css/cart.css">
@endsection

@section('content')
    <div style="background-color: #fef3f0; min-height: 100vh; padding: 3rem 0;">
        <div class="container my-5">
            <div class="row g-4">

                {{-- Left Column: Order Details --}}
                <div class="col-lg-8">
                    <div class="card p-3 border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pt-3">
                            <h4 class="fw-bold mb-0">
                                <i class="bi bi-receipt me-2" style="color: #e74c3c;"></i>
                                Order Summary
                            </h4>
                        </div>

                        <div class="card-body">
                            @foreach ($order->items as $item)
                                <div class="d-flex align-items-start mb-4 border-bottom pb-3 last:border-0">

                                    {{-- <img src="{{ asset('storage/' . $item->meal->image_url) }}"
                                        alt="{{ $item->meal_name }}" class="rounded"
                                        style="width: 80px; height: 80px; object-fit: cover;"> --}}

                                    <div class="flex-grow-1 ms-2">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="fw-bold mb-1">{{ $item->meal_name }}</h6>
                                            <span class="fw-bold">x {{ $item->quantity }}</span>
                                        </div>

                                        <p class="text-muted small mb-2">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </p>

                                        {{-- Options List --}}
                                        @if ($item->options && $item->options->isNotEmpty())
                                            <div class="small text-muted bg-light p-2 rounded">
                                                @foreach ($item->options as $option)
                                                    <div>
                                                        • {{ $option->option_name }}
                                                        @if($option->price > 0)
                                                            <span class="fw-bold text-dark">
                                                                (+Rp {{ number_format($option->price, 0, ',', '.') }})
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
                                            // Add option prices if necessary logic exists, 
                                            // otherwise this displays base price * qty
                                            foreach ($item->options as $opt) {
                                                $itemTotal += $opt->price * $item->quantity;
                                            }
                                        @endphp
                                        <span class="fw-bold text-primary">
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
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Payment Details</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Order ID</span>
                                <span class="fw-bold text-dark">#{{ $order->id }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total Items</span>
                                <span class="fw-bold">{{ $order->items->sum('quantity') }} items</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between fw-bold fs-5 mt-3 mb-4">
                                <span>Total Amount</span>
                                <span style="color: #e74c3c;">Rp
                                    {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>

                            {{-- Pay Button --}}
                            <button id="pay-button" class="btn btn-primary w-100 py-2 fw-bold"
                                style="background-color: #0d6efd;">
                                <i class="bi bi-credit-card-2-front me-2"></i> Pay Now
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
                    alert('You closed the popup without finishing the payment');
                }
            });
        });
    </script>
@endsection