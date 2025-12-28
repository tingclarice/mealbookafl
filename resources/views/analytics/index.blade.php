@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h1 class="mb-3 mb-md-0">Analytics Dashboard</h1>

            <!-- Date Filter -->
            <div class="d-flex flex-column flex-md-row gap-2">
                <!-- Quick Filters -->
                <div class="btn-group" role="group">
                    <a href="{{ route('analytics', ['start_date' => now()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="btn btn-sm btn-outline-primary">Today</a>
                    <a href="{{ route('analytics', ['start_date' => now()->subDays(7)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="btn btn-sm btn-outline-primary">7 Days</a>
                    <a href="{{ route('analytics', ['start_date' => now()->subDays(30)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="btn btn-sm btn-outline-primary">30 Days</a>
                </div>

                <!-- Custom Date Range -->
                <form method="GET" action="{{ route('analytics') }}" class="d-flex gap-2">
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                    <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                </form>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Revenue -->
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-muted mb-0">Total Revenue</h6>
                            <i class="bi bi-currency-dollar text-success fs-4"></i>
                        </div>
                        <h3 class="mb-2">Rp {{ number_format($analytics['revenue']['total'], 0, ',', '.') }}</h3>
                        <small class="text-{{ $analytics['revenue']['growth_rate'] >= 0 ? 'success' : 'danger' }}">
                            <i
                                class="bi bi-{{ $analytics['revenue']['growth_rate'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($analytics['revenue']['growth_rate']) }}% vs previous period
                        </small>
                    </div>
                </div>
            </div>

            <!-- Today's Revenue -->
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-muted mb-0">Today's Revenue</h6>
                            <i class="bi bi-calendar-check text-primary fs-4"></i>
                        </div>
                        <h3 class="mb-2">Rp {{ number_format($analytics['revenue']['today'], 0, ',', '.') }}</h3>
                        <small class="text-muted">Updated in real-time</small>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-muted mb-0">Total Orders</h6>
                            <i class="bi bi-bag-check text-info fs-4"></i>
                        </div>
                        <h3 class="mb-2">{{ number_format($analytics['orders']['total']) }}</h3>
                        <small class="text-muted">{{ $analytics['orders']['today'] }} orders today</small>
                    </div>
                </div>
            </div>

            <!-- Average Order Value -->
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-muted mb-0">Avg Order Value</h6>
                            <i class="bi bi-graph-up text-warning fs-4"></i>
                        </div>
                        <h3 class="mb-2">Rp {{ number_format($analytics['revenue']['average_order_value'], 0, ',', '.') }}
                        </h3>
                        <small class="text-muted">{{ $analytics['orders']['completion_rate'] }}% completion rate</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Revenue Trend Chart -->
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Revenue Trend</h5>
                        <canvas id="revenueTrendChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Order Status Distribution -->
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Order Status</h5>
                        <canvas id="orderStatusChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Top Selling Products</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Product Name</th>
                                <th class="text-end">Quantity Sold</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analytics['products']['top_selling'] as $index => $product)
                                <tr>
                                    <td>
                                        <span
                                            class="badge bg-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'light text-dark') }}">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $product->meal_name }}</strong></td>
                                    <td class="text-end">{{ $product->total_sold }} units</td>
                                    <td class="text-end"><strong>Rp {{ number_format($product->revenue, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No sales data available for this period
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Revenue Trend Chart
            const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
            new Chart(revenueTrendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($analytics['charts']['revenue_trend']->pluck('date')) !!},
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: {!! json_encode($analytics['charts']['revenue_trend']->pluck('revenue')) !!},
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });

            // Order Status Chart
            const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const statusData = {!! json_encode($analytics['orders']['status_distribution']) !!};

            new Chart(orderStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusData),
                    datasets: [{
                        data: Object.values(statusData),
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.8)',   // PENDING - Yellow
                            'rgba(54, 162, 235, 0.8)',   // CONFIRMED - Blue
                            'rgba(75, 192, 192, 0.8)',   // READY - Teal
                            'rgba(153, 102, 255, 0.8)',  // CANCELLED - Purple
                            'rgba(75, 192, 75, 0.8)'     // COMPLETED - Green
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection