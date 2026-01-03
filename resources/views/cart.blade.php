@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="css/cart.css">
    <style>
        /* Custom radio styling to make it pop */
        .shop-radio {
            width: 1.2em;
            height: 1.2em;
            cursor: pointer;
            accent-color: #e74c3c;
        }
        .shop-group-card {
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }
        .shop-group-card.selected-shop {
            border: 2px solid #e74c3c;
            background-color: #fff5f5;
        }
    </style>
@endsection

@section('content')
    {{-- Alerts (Success/Error/Validation) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div style="background-color: #fef3f0; min-height: 100vh; padding: 3rem 0;">
        <div class="container my-5 min-h-screen">
            <div class="row g-4">
                
                {{-- LEFT COLUMN: Cart Items Grouped by Shop --}}
                <div class="col-lg-8 mx-auto">
                    
                    @forelse ($groupedCartItems as $shopId => $items)
                        @php
                            $shop = $items->first()->meal->shop;
                            // Calculate subtotal for THIS specific shop
                            $shopSubtotal = $items->sum(function($item){ return $item->total_price; });
                        @endphp

                        {{-- Shop Card Wrapper --}}
                        <div class="card p-3 mb-4 shop-group-card" id="shop-card-{{ $shopId }}">
                            
                            {{-- Card Header with Radio Button --}}
                            <div class="card-header bg-transparent border-bottom-0 pt-3 pb-2">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <input type="radio" 
                                            name="shop_selection" 
                                            id="shop_radio_{{ $shopId }}" 
                                            class="shop-radio"
                                            value="{{ $shopId }}"
                                            data-subtotal="{{ $shopSubtotal }}"
                                            onchange="updateSummary(this)">
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-0 text-dark">
                                            <i class="bi bi-shop me-2" style="color: #e74c3c;"></i>
                                            {{ $shop->name }}
                                        </h5>
                                        <small class="text-muted">{{ $items->count() }} items</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                {{-- Loop Items for this Shop --}}
                                @foreach ($items as $item)
                                    <div class="d-flex align-items-center mb-4">
                                        {{-- Image --}}
                                        <a href="{{ route('menu.show', $item->meal->id) }}">
                                            <img src="{{ asset('storage/' . $item->meal->image_url) }}"
                                                alt="{{ $item->meal->name }}" class="cart-item-img">
                                        </a>

                                        {{-- Details --}}
                                        <div class="flex-grow-1 ms-3">
                                            <a href="{{ route('menu.show', $item->meal->id) }}" class="text-decoration-none text-dark">
                                                <h6 class="fw-bold mb-1">{{ $item->meal->name }}</h6>
                                            </a>

                                            {{-- Options Display --}}
                                            @if ($item->selectedOptions->isNotEmpty())
                                                <div class="small text-muted">
                                                    @foreach ($item->selectedOptions as $selectedOption)
                                                        @if ($selectedOption->optionValue && $selectedOption->optionValue->group)
                                                            • {{ $selectedOption->optionValue->group->name }}:
                                                            {{ $selectedOption->optionValue->name }}
                                                            @if ($selectedOption->optionValue->price > 0)
                                                                (+Rp {{ number_format($selectedOption->optionValue->price, 0, ',', '.') }})
                                                            @endif
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Notes --}}
                                            @if ($item->notes)
                                                <div class="small text-muted fst-italic mt-1">
                                                    Note: {{ $item->notes }}
                                                </div>
                                            @endif

                                            <p class="text-muted small mb-2">
                                                Rp. {{ number_format($item->meal->price, 0, ',', '.') }} x {{ $item->quantity }}
                                            </p>

                                            {{-- Quantity Controls --}}
                                            <div class="d-flex align-items-center">
                                                <form action="{{ route('cart.decrement', $item->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary quantity-btn">−</button>
                                                </form>

                                                <span class="mx-3 fw-bold">{{ $item->quantity }}</span>

                                                <form action="{{ route('cart.increment', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary quantity-btn">+</button>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- Total Price for Item --}}
                                        <div class="ms-3">
                                            <span class="fw-bold">{{ $item->formatted_total_price }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @empty
                        <div class="card p-5 text-center">
                            <h4>Your cart is empty</h4>
                            <a href="/" class="btn btn-primary mt-3">Browse Food</a>
                        </div>
                    @endforelse
                </div>

                {{-- RIGHT COLUMN: Payment Summary --}}
                @if ($groupedCartItems->isNotEmpty())
                    <div class="col-lg-4">
                        <div class="card shadow-sm sticky-top" style="top: 20px;">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4">Payment Summary</h5>
                                
                                {{-- Dynamic Values Span --}}
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold" id="summary-subtotal">Rp 0</span>
                                </div>
                                @if($fee > 0)
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Fee</span>
                                    <span class="fw-bold">Rp {{ number_format($fee, 0, ',', '.') }}</span>
                                </div>
                                @endif

                                <hr>

                                <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
                                    <span>Total Price</span>
                                    <span class="fw-bold" id="summary-total">Rp 0</span>
                                </div>

                                <div id="select-shop-warning" class="alert alert-warning mt-3 small py-2">
                                    Please select a shop to checkout.
                                </div>

                                {{-- Checkout Form --}}
                                <form id="checkout-form" action="#" method="POST">
                                    @csrf
                                    <input type="hidden" name="shop_id" id="selected-shop-input" required>
                                    
                                    <button type="submit" 
                                            id="checkout-btn"
                                            class="btn btn-primary w-100 mt-4 checkout-btn" 
                                            disabled>
                                        Check Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Javascript for Dynamic Calculation --}}
    <script>
        const FEE = {{ $fee }};

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function updateSummary(radioButton) {
            // 1. Get Data
            const subtotal = parseFloat(radioButton.getAttribute('data-subtotal'));
            const shopId = radioButton.value;
            const total = subtotal + FEE;

        
            let routeUrl = "{{ route('order.create', ['shop' => 0]) }}";

            routeUrl = routeUrl.replace('/0', '/' + shopId); 

            // Update the form action
            document.getElementById('checkout-form').action = routeUrl;
            

            // 2. Update Summary UI
            document.getElementById('summary-subtotal').innerText = formatRupiah(subtotal);
            document.getElementById('summary-total').innerText = formatRupiah(total);
            document.getElementById('selected-shop-input').value = shopId;

            // 3. Enable Checkout
            document.getElementById('checkout-btn').disabled = false;
            document.getElementById('select-shop-warning').style.display = 'none';

            // 4. Visual Selection
            document.querySelectorAll('.shop-group-card').forEach(card => {
                card.classList.remove('selected-shop');
            });
            document.getElementById('shop-card-' + shopId).classList.add('selected-shop');
        }
    </script>
@endsection