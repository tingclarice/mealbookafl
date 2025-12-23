<div class="list-group-item p-4 shop-order-card border-bottom">
    <div class="d-flex justify-content-between align-items-start">

        {{-- Left Side: Order Info --}}
        <div>
            <div class="d-flex align-items-center mb-2">
                <span class="badge bg-light text-dark border me-2">
                    #{{ $order->id }}
                </span>
                <span class="text-muted small">
                    {{ $order->created_at->format('d M Y, H:i') }}
                </span>
            </div>

            {{-- Customer Name (Assuming Order has user relationship) --}}
            <h5 class="fw-bold mb-1">{{ $order->user->name ?? 'Guest Customer' }}</h5>
            <div class="text-muted small mb-3">
                Payment: <span class="fw-semibold text-dark">{{ $order->payment_method ?? 'N/A' }}</span>
            </div>

            {{-- Items List --}}
            <div class="bg-light rounded p-3 mb-2">
                {{-- Assuming a relationship like $order->items or $order->meals --}}
                @foreach($order->items as $item)
                    <div class="d-flex justify-content-between mb-1">
                        <span>
                            <span class="fw-bold text-dark">{{ $item->quantity }}x</span>
                            {{ $item->meal_name }}
                        </span>
                    </div>
                @endforeach

                @if($order->notes)
                    <div class="mt-2 text-danger small fst-italic">
                        <strong>Note:</strong> {{ $order->notes }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Side: Total & Status --}}
        <div class="text-end">
            <div class="mb-3">
                <small class="text-muted d-block">Total Amount</small>
                <h5 class="fw-bold" style="color: #F97352;">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </h5>
            </div>

            {{-- Status Badge --}}
            @php
                $statusColor = match ($order->status) {
                    'PENDING' => 'warning',
                    'COOKING' => 'info',
                    'READY' => 'primary',
                    'COMPLETED' => 'success',
                    'CANCELLED' => 'danger',
                    default => 'secondary',
                };
            @endphp
            <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} px-3 py-2 rounded-pill">
                {{ $order->status }}
            </span>
        </div>
    </div>
</div>