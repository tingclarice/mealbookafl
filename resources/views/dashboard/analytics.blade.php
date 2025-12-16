@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Shop Analytics</h2>
            <p class="text-muted mb-0">{{ $shop->name }}</p>
        </div>
        
        {{-- Date Range Filter --}}
        <form method="GET" action="{{ route('dashboard.analytics') }}" class="d-flex gap-2">
            <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
            <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        {{-- Total Revenue --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Revenue</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cash-stack text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Orders</p>
                            <h3 class="fw-bold mb-0">{{ $stats['total_orders'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-receipt text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Completed Orders --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Completed</p>
                            <h3 class="fw-bold mb-0">{{ $stats['completed_orders'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Average Order Value --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Avg Order Value</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($stats['avg_order_value'], 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-graph-up text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Status Overview --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-clock-history text-warning fs-3 mb-2"></i>
                    <h4 class="fw-bold mb-0">{{ $stats['pending_orders'] }}</h4>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-credit-card text-success fs-3 mb-2"></i>
                    <h4 class="fw-bold mb-0">{{ $stats['paid_orders'] }}</h4>
                    <small class="text-muted">Paid</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-hourglass-split text-primary fs-3 mb-2"></i>
                    <h4 class="fw-bold mb-0">{{ $stats['preparing_orders'] }}</h4>
                    <small class="text-muted">Preparing</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-bag-check text-info fs-3 mb-2"></i>
                    <h4 class="fw-bold mb-0">{{ $stats['ready_orders'] }}</h4>
                    <small class="text-muted">Ready</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Revenue Chart --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="fw-bold mb-0">Revenue Trend (Last 7 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Selling Meals --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="fw-bold mb-0">Top Selling Meals</h5>
                </div>
                <div class="card-body">
                    @forelse($topMeals as $meal)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <img src="{{ asset('storage/' . $meal->image_url) }}" alt="{{ $meal->name }}" 
                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-0 fw-bold">{{ $meal->name }}</h6>
                                <small class="text-muted">{{ $meal->total_sold }} sold</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success">Rp {{ number_format($meal->total_revenue, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">No sales data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header bg-white border-0 pt-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Orders</h5>
                <a href="{{ route('dashboard.orders') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->items->count() }} items</td>
                                <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $order->order_status === 'COMPLETED' ? 'success' : 
                                        ($order->order_status === 'PENDING' ? 'warning' : 'primary') 
                                    }}">
                                        {{ $order->order_status }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No orders yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dailyRevenue->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
        datasets: [{
            label: 'Revenue (Rp)',
            data: {!! json_encode($dailyRevenue->pluck('revenue')) !!},
            borderColor: '#F97352',
            backgroundColor: 'rgba(249, 115, 82, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endsection