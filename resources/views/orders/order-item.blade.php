@php
    $paymentStatus = $order->payment_status; // PENDING, PAID, FAILED, EXPIRED, CANCELLED
    $orderStatus = $order->order_status;   // PENDING, CONFIRMED, READY, COMPLETED

    $color = 'secondary';
    $text = $orderStatus;
    $isActionable = false; // For Pay Button

    if ($paymentStatus === 'PENDING' && $orderStatus !== 'CANCELLED') {
        $color = 'warning';
        $text = 'Waiting for Payment';
        $isActionable = true;
    } elseif ($paymentStatus === 'FAILED' || $paymentStatus === 'EXPIRED' || $paymentStatus === 'CANCELLED') {
        $color = 'danger';
        $text = 'Payment ' . ucfirst(strtolower($paymentStatus));
    } else {
        switch ($orderStatus) {
            case 'CANCELLED':
                $color = 'danger';
                $text = 'Order Cancelled';
                break;
            case 'PENDING':
                $color = 'info';
                $text = 'Order Placed';
                break;
            case 'CONFIRMED':
                $color = 'primary';
                $text = 'Cooking';
                break;
            case 'READY':
                $color = 'success';
                $text = 'Ready for Pickup';
                break;
            case 'COMPLETED':
                $color = 'dark';
                $text = 'Completed';
                break;
            default:
                $text = $orderStatus;
        }
    }

    // Safety check for items
    $firstItem = $order->items->first();
    $itemCount = $order->items->count();
@endphp

<div class="list-group-item p-4 border-bottom order-card bg-white" style="cursor: pointer;"
    onclick="window.location='{{ route('order.details', $order->id) }}'">
    <div class="row align-items-center">

        {{-- Column 1: Image & Main Info --}}
        <div class="col-md-5 mb-3 mb-md-0">
            <div class="d-flex align-items-center">
                {{-- Icon/Image Placeholder --}}
                <div class="me-3 flex-shrink-0">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                        style="width: 64px; height: 64px; background-color: #fff4f2; color: #F97352;">
                        <i class="bi bi-bag-check-fill fs-3"></i>
                    </div>
                </div>
                <div>
                    {{-- Title: Order ID --}}
                    <h6 class="fw-bold mb-1 text-dark">
                        Order #{{ $order->id }}
                    </h6>

                    {{-- Subtitle: First Item Name or Generic --}}
                    <small class="text-muted d-block text-truncate" style="max-width: 200px;">
                        @if($firstItem)
                            {{-- Assuming OrderItem has 'product_name' or similar, adapt as needed --}}
                            {{ $firstItem->product_name ?? 'Item List' }}
                            @if($itemCount > 1)
                                <span class="fw-semibold">+{{ $itemCount - 1 }} more</span>
                            @endif
                        @else
                            No items
                        @endif
                    </small>

                    {{-- Date --}}
                    <small class="text-muted">
                        {{ $order->created_at
                            ->timezone(session('timezone', config('app.timezone')))
                            ->format('d M Y, H:i') 
                        }}
                    </small>
                </div>
            </div>
        </div>

        {{-- Column 2: Price & Payment Method --}}
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="small text-muted mb-1">Total Amount</div>
            <h6 class="fw-bold text-dark mb-1">
                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </h6>
            <small class="badge bg-light text-muted border">
                {{ $order->payment_method ?? 'Unknown Payment' }}
            </small>
        </div>

        {{-- Column 3: Status & Buttons --}}
        <div class="col-md-4 text-md-end">
            {{-- Status Badge --}}
            <span
                class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-3 py-2 rounded-pill fw-bold mb-2 d-inline-block">
                {{ $text }}
            </span>

            {{-- Action Buttons --}}
            @if($order->order_status !== 'CANCELLED')
            <div class="mt-1">
                {{-- 1. PAY NOW (Midtrans) --}}
                @if($paymentStatus === 'PENDING' && $order->snap_token)
                    <button id="pay-button-{{ $order->id }}"
                        class="btn btn-sm text-white fw-bold px-4 rounded-pill shadow-sm" style="background-color: #F97352;"
                        onclick="event.stopPropagation(); startPayment('{{ $order->snap_token }}')">
                        Pay Now
                    </button>

                    {{-- 2. SHOW QR (Only when READY) --}}
                @elseif($orderStatus === 'READY')
                    <button class="btn btn-sm btn-outline-dark fw-bold px-3 rounded-pill"
                        onclick="event.stopPropagation(); showQr('#{{ $order->id }}', '{{ $order->midtrans_order_id }}')">
                        <i class="bi bi-qr-code me-1"></i> Show QR
                    </button>
                @endif
            </div>
            @endif
            

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