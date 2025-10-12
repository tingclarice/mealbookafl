@extends('layouts.app')

@section('content')
{{-- Adding some custom styles to match your design --}}
<style>
    body {
        background-color: #f8f9fa; /* Light gray background */
    }
    .card {
        border: none;
        border-radius: 1rem; /* More rounded corners for cards */
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .cart-item-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.75rem;
    }
    .quantity-btn {
        width: 28px;
        height: 28px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    .checkout-btn {
        background-color: #2c3e50; /* Dark blue-gray button */
        border: none;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        font-weight: bold;
    }
    .checkout-btn:hover {
        background-color: #34495e;
    }
</style>

<div class="container my-5 min-h-screen">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-3">
                <div class="card-header bg-white border-0 pt-3">
                    <h4 class="fw-bold mb-0">
                        <i class="bi bi-list-ul me-2" style="color: #e74c3c;"></i>
                        Your selected meals
                    </h4>
                </div>
                <div class="card-body">
                    {{-- Looping Cart Items --}}
                    @forelse ($cartItems as $item)
                        <div class="d-flex align-items-center mb-4">
                            {{-- Access the meal relationship for image, name, and price --}}
                            <img src="{{ asset($item->meal->image_url) }}" alt="{{ $item->meal->name }}" class="cart-item-img">

                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-bold mb-1">{{ $item->meal->name }}</h6>
                                <p class="text-muted small mb-2">
                                    Rp. {{ number_format($item->meal->price, 0, ',', '.') }} x {{ $item->quantity }}
                                </p>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-sm btn-outline-secondary quantity-btn">-</button>
                                    <span class="mx-3 fw-bold">{{ $item->quantity }}</span>
                                    <button class="btn btn-sm btn-outline-secondary quantity-btn">+</button>
                                </div>
                            </div>

                            <div class="ms-3">
                                {{-- Calculate total price on the fly --}}
                                <span class="fw-bold">Rp. {{ number_format($item->meal->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        {{-- ... --}}
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column: Payment Summary --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Payment Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Fee</span>
                        <span class="fw-bold">Rp {{ number_format($fee, 0, ',', '.') }}</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
                        <span>Total Price</span>
                        <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <a href="#" class="btn btn-primary w-100 mt-4 checkout-btn">Check Out</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection