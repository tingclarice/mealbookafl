@extends('layouts.app')

@section('content')
<style>
    :root {
        --brand-primary: #F97352;
        --brand-light: #fff5f2;
    }
    .text-brand { color: var(--brand-primary); }
    .bg-brand { background-color: var(--brand-primary); }
    .btn-brand { 
        background-color: var(--brand-primary); 
        color: white; 
        border: none;
        transition: all 0.3s ease;
    }
    .btn-brand:hover { 
        background-color: #e65f3e; 
        color: white; 
        box-shadow: 0 4px 12px rgba(249, 115, 82, 0.2);
    }
    .wallet-card {
        border-radius: 16px;
        overflow: hidden;
    }
    .transaction-row {
        transition: background 0.2s ease;
    }
    .transaction-row:hover {
        background-color: #fafafa;
    }
    .icon-box {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Shop Wallet</h3>
                    <p class="text-muted mb-0">View your transaction history for <strong>{{ $shop->name }}</strong></p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card wallet-card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="text-muted fw-medium">Available Balance</span>
                            </div>
                            <h3 class="display-6 fw-bold mb-1">
                                Rp {{ number_format($wallet->balance, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card wallet-card border-0 shadow-sm h-100 bg-light">
                        <div class="card-body p-4">
                            <span class="text-muted fw-medium d-block mb-3">Pending Settlement</span>
                            <h3 class="display-6 fw-bold text-secondary mb-1">
                                Rp {{ number_format($wallet->pending_balance, 0, ',', '.') }}
                            </h3>
                            <p class="text-muted small mb-0">Will be available after order is Ready</p>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card wallet-card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 p-4">
                            <h5 class="fw-bold mb-0">Transaction History</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 ps-4 py-3 text-muted small text-uppercase">Activity</th>
                                        <th class="border-0 py-3 text-muted small text-uppercase">Type</th>
                                        <th class="border-0 py-3 text-muted small text-uppercase">Date</th>
                                        <th class="border-0 pe-4 py-3 text-end text-muted small text-uppercase">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $transaction)
                                    <tr class="transaction-row">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box {{ $transaction->type == 'credit' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 me-3">
                                                    <i class="bi {{ $transaction->type == 'credit' ? 'bi-arrow-down-left text-success' : 'bi-arrow-up-right text-danger' }}"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $transaction->description }}</div>
                                                    <div class="text-muted small">Ref: #TRX-{{ $transaction->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($transaction->type == 'credit')
                                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3">Income</span>
                                            @else
                                                <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3">Payout</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">
                                            {{ $transaction->created_at->format('d M, Y') }}<br>
                                            <span class="opacity-75">{{ $transaction->created_at->format('H:i') }}</span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <span class="fw-bold fs-6 {{ $transaction->type == 'credit' ? 'text-success' : 'text-dark' }}">
                                                {{ $transaction->type == 'credit' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-wallet2 opacity-25" style="font-size: 5rem; color: #F97352;"></i>
                                            </div>
                                            
                                            <h5 class="fw-bold text-secondary mb-1">No Transactions Yet</h5>
                                            <p class="text-muted small">Your wallet activity will appear here once you start selling.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($transactions->hasPages())
                        <div class="card-footer bg-white border-0 p-4">
                            {{ $transactions->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div> </div>
    </div>
</div>
@endsection