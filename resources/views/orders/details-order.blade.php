@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <style>
        .invoice-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .invoice-header {
            background-color: #F97352;
            color: white;
            padding: 2rem;
        }

        .status-badge {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            backdrop-filter: blur(5px);
        }

        .invoice-body {
            padding: 2rem;
        }

        .table-invoice th {
            font-weight: 600;
            color: #888;
            border-bottom-width: 1px;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding-bottom: 1rem;
        }

        .table-invoice td {
            padding: 1.25rem 0.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-invoice tr:last-child td {
            border-bottom: none;
        }

        .amount-row {
            font-size: 1.1rem;
        }

        .total-row {
            background-color: #fff9f7;
            border-radius: 8px;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Back Button --}}
                <div class="mb-4">
                    <a href="{{ url()->previous() }}" class="text-decoration-none text-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Back
                    </a>
                </div>

                <div class="invoice-card">
                    {{-- Header --}}
                    <div class="invoice-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1 text-white opacity-75">Order Invoice</h5>
                                <h3 class="fw-bold mb-0">#{{ $order->id }}</h3>
                                <div class="mt-2 text-white-50">
                                    {{ $order->created_at->format('F d, Y \a\t H:i') }}
                                </div>
                            </div>
                            <div class="text-end">
                                @php
                                    $statusText = $order->order_status;
                                    if ($order->payment_status === 'PENDING') {
                                        $statusText = 'Unpaid';
                                    } elseif ($order->payment_status === 'FAILED') {
                                        $statusText = 'Payment Failed';
                                    } elseif ($order->order_status === 'CONFIRMED') {
                                        $statusText = 'Cooking';
                                    } elseif ($order->order_status === 'READY') {
                                        $statusText = 'Ready for Pickup';
                                    }
                                @endphp
                                <span class="status-badge">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="invoice-body">
                        
                        {{-- Customer Info --}}
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <h6 class="text-secondary text-uppercase small fw-bold mb-3">Billed To</h6>
                                <h5 class="fw-bold text-dark mb-1">{{ $order->user->name }}</h5>
                                <p class="text-muted mb-0">{{ $order->user->email }}</p>
                            </div>
                            <div class="col-md-6 text-md-end mt-4 mt-md-0">
                                <h6 class="text-secondary text-uppercase small fw-bold mb-3">Payment Info</h6>
                                <p class="mb-1">
                                    <span class="text-muted">Status:</span> 
                                    <span class="fw-semibold {{ $order->payment_status == 'PAID' ? 'text-success' : 'text-danger' }}">
                                        {{ $order->payment_status }}
                                    </span>
                                </p>
                                <p class="mb-1">
                                    <span class="text-muted">Method:</span> 
                                    <span class="fw-semibold text-dark">{{ $order->payment_method ?? '-' }}</span>
                                </p>
                                @if($order->payment_time)
                                <p class="mb-0">
                                    <span class="text-muted">Paid at:</span> 
                                    <span class="fw-semibold text-dark">{{ $order->payment_time->format('d M Y, H:i') }}</span>
                                </p>
                                @endif
                            </div>
                        </div>

                        {{-- Order Items --}}
                        <div class="table-responsive mb-4">
                            <table class="table table-invoice mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Item Description</th>
                                        <th class="text-center">Rate</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $item->meal_name }}</div>
                                                @if($item->options->isNotEmpty())
                                                    <div class="small text-muted mt-1">
                                                        @foreach($item->options as $option)
                                                            <div>+ {{ $option->option_name }} @ Rp {{ number_format($option->price, 0, ',', '.') }}</div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @if($item->review_msg)
                                                     <div class="small text-info mt-1 fst-italic">Note: "{{ $item->review_msg }}"</div>
                                                @endif
                                            </td>
                                            <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end fw-semibold">
                                                Rp {{ number_format($item->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Total Calculation --}}
                        <div class="row">
                            <div class="col-md-6 ms-auto">
                                <div class="total-row p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-0">
                                        <span class="fw-bold fs-5 text-dark">Total</span>
                                        <span class="fw-bold fs-4" style="color: #F97352;">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-5 text-end d-flex justify-content-end gap-2">
                            {{-- Buyer Actions --}}
                            @if(auth()->id() === $order->user_id)
                                @if($order->payment_status === 'PENDING' && $order->snap_token)
                                    <button id="pay-button" 
                                        class="btn text-white fw-bold px-5 py-2 rounded-pill shadow-sm"
                                        style="background-color: #F97352;"
                                        onclick="startPayment('{{ $order->snap_token }}')">
                                        Pay Now
                                    </button>
                                @elseif($order->order_status === 'READY')
                                    <button class="btn btn-outline-success fw-bold px-4 py-2 rounded-pill">
                                        <i class="bi bi-qr-code me-2"></i> Show Pickup QR
                                    </button>
                                @endif
                            @endif

                            {{-- Seller Actions --}}
                            @if($order->isStaffOrOwner())
                                @if($order->order_status === 'PENDING')
                                    @if($order->payment_status === 'PAID')
                                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-pill">
                                                Confirm Order
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Waiting for Payment</span>
                                    @endif
                                @elseif($order->order_status === 'CONFIRMED')
                                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-info text-white fw-bold px-4 py-2 rounded-pill">
                                            Mark as Ready
                                        </button>
                                    </form>
                                @elseif($order->order_status === 'READY')
                                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success fw-bold px-4 py-2 rounded-pill">
                                            Complete Order
                                        </button>
                                    </form>
                                @elseif($order->order_status === 'COMPLETED')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">Order Completed</span>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        function startPayment(token) {
            snap.pay(token, {
                onSuccess: function (result) {
                    location.reload();
                },
                onPending: function (result) {
                    location.reload();
                },
                onError: function (result) {
                    alert('Payment failed');
                    location.reload();
                },
                onClose: function () {
                    location.reload();
                }
            });
        }
    </script>

@endsection