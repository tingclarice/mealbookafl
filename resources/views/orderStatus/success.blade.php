@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="css/settings.css">

    <div class="container py-5">
        <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
            <div class="col-md-6 col-lg-5">

                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-body p-5 text-center">

                        {{-- Animated Success Icon --}}
                        <div class="mb-4 d-inline-block p-4 rounded-circle" style="background-color: #ecfdf5;">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>

                        {{-- Main Heading --}}
                        <h3 class="fw-bold mb-2" style="color: #333;">Payment Settled!</h3>

                        {{-- The Specific Message Requested --}}
                        <p class="text-muted mb-4" style="font-size: 1.1rem; line-height: 1.6;">
                            Thank you for ordering, your payment is settled. <br>
                            We will inform you to get ready the food.
                        </p>

                        {{-- Optional: Order ID Reference --}}
                        <div class="py-2 px-3 rounded-3 mb-4 d-inline-block"
                            style="background-color: #f8f9fa; border: 1px dashed #dee2e6;">
                            <span class="text-muted small text-uppercase fw-bold">Order ID:</span>
                            <span class="fw-bold" style="color: #333;">#{{ $order->id ?? 'ORD-' . rand(1000, 9999) }}</span>
                        </div>

                        {{-- Action Button (Styled with your brand orange #F97352) --}}
                        <div class="d-grid gap-2">
                            <a href="{{ url('/') }}" class="btn text-white fw-bold py-2"
                                style="background-color: #F97352; border-radius: 8px; transition: 0.3s;">
                                Back to Home
                            </a>

                            {{-- Secondary Link --}}
                            <a href="#" class="btn btn-link text-decoration-none text-muted mt-2"
                                style="font-size: 0.9rem;">
                                View Order Details
                            </a>
                        </div>

                    </div>

                    {{-- Decorative Footer Strip --}}
                    <div class="card-footer border-0 p-0" style="height: 6px; background-color: #F97352;"></div>
                </div>

            </div>
        </div>
    </div>
@endsection