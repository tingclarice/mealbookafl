@extends('layouts.app')

@section('content')
    <style>
        /* Custom Color Variables */
        :root {
            --brand-primary: #F97352;
            --brand-hover: #e65f3e;
            --brand-light: #fff3f0;
        }

        .text-brand {
            color: var(--brand-primary) !important;
        }

        .bg-brand {
            background-color: var(--brand-primary) !important;
        }

        .btn-brand {
            background-color: var(--brand-primary);
            border-color: var(--brand-primary);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-brand:hover {
            background-color: var(--brand-hover);
            border-color: var(--brand-hover);
            color: white;
            transform: translateY(-1px);
        }

        .btn-outline-brand {
            color: var(--brand-primary);
            border-color: var(--brand-primary);
        }

        .btn-outline-brand:hover {
            background-color: var(--brand-primary);
            color: white;
        }

        /* Elegant Tweaks */
        .card {
            border-radius: 12px;
        }

        .shop-avatar {
            width: 45px;
            height: 45px;
            background-color: var(--brand-light);
            color: var(--brand-primary);
            font-weight: bold;
        }

        .form-control:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 0.25 red;
            /* Use brand glow */
            box-shadow: 0 0 0 0.2rem rgba(249, 115, 82, 0.15);
        }

        .table thead th {
            background-color: #fafafa;
            letter-spacing: 0.05em;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex align-items-center mb-4">
                    <h3 class="fw-bold mb-0">Money Withdrawal</h3>
                </div>

                {{-- Search Bar --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search text-brand"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" id="search-shop"
                                placeholder="Search active shops..." onkeyup="searchShops()">
                        </div>
                    </div>
                </div>

                {{-- Shops Table --}}
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0 small text-uppercase text-muted fw-bold ps-4 py-3">Shop Name
                                        </th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold py-3">Wallet Balance
                                        </th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold text-end pe-4 py-3">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody id="shops-table-body">
                                    @forelse ($shops as $shop)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="rounded-circle me-3 d-flex align-items-center justify-content-center shop-avatar">
                                                        {{ substr($shop->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark">{{ $shop->name }}</div>
                                                        <div class="small text-muted">{{ $shop->address }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <span class="badge rounded-pill px-3 py-2 fw-medium"
                                                    style="background-color: var(--brand-light); color: var(--brand-primary); border: 1px solid rgba(249, 115, 82, 0.2);">
                                                    Rp {{ number_format($shop->wallet->balance ?? 0, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4 py-3">
                                                <button class="btn btn-sm btn-outline-brand px-3"
                                                    onclick="openWithdrawModal('{{ $shop->id }}', '{{ $shop->name }}', '{{ $shop->wallet->balance ?? 0 }}')">
                                                    <i class="bi bi-cash-stack me-1"></i> Withdraw
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">No active shops found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Withdraw Modal --}}
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Withdraw Money</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="text-muted mb-4">You are withdrawing from <strong id="modal-shop-name"
                            class="text-brand"></strong>.</p>

                    <form action="{{ route('admin.withdrawal.store') }}" method="POST" id="withdrawForm">
                        @csrf
                        <input type="hidden" name="shop_id" id="modal-shop-id">

                        <div class="mb-4">
                            <label class="form-label small text-uppercase text-muted fw-bold">Amount (Rp)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-end-0 fw-bold text-muted">Rp</span>
                                <input type="number" class="form-control border-start-0 fw-bold" name="amount"
                                    id="withdrawal-amount" placeholder="0" required min="1">
                            </div>
                            <div class="form-text mt-2">Available Balance: <span class="fw-bold text-dark">Rp <span
                                        id="modal-balance">0</span></span></div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-3">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-brand px-4">Confirm Withdrawal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let searchTimeout;

        function searchShops() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = document.getElementById('search-shop').value;

                fetch(`{{ route('admin.withdrawal.search') }}?query=${query}`, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderTable(data.shops);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }, 300);
        }

        function renderTable(shops) {
            const tbody = document.getElementById('shops-table-body');
            tbody.innerHTML = '';

            if (shops.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-muted">No shops found.</td></tr>';
                return;
            }

            shops.forEach(shop => {
                const balance = shop.wallet ? shop.wallet.balance : 0;
                const formattedBalance = new Intl.NumberFormat('id-ID').format(balance);

                const html = `
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center shop-avatar">
                                                    ${shop.name.charAt(0)}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">${shop.name}</div>
                                                    <div class="small text-muted">${shop.address || ''}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge rounded-pill px-3 py-2 fw-medium" 
                                                  style="background-color: var(--brand-light); color: var(--brand-primary); border: 1px solid rgba(249, 115, 82, 0.2);">
                                                Rp ${formattedBalance}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <button class="btn btn-sm btn-outline-brand px-3" 
                                                onclick="openWithdrawModal('${shop.id}', '${shop.name}', '${balance}')">
                                                <i class="bi bi-cash-stack me-1"></i> Withdraw
                                            </button>
                                        </td>
                                    </tr>`;
                tbody.insertAdjacentHTML('beforeend', html);
            });
        }

        function openWithdrawModal(shopId, shopName, balance) {
            const modal = new bootstrap.Modal(document.getElementById('withdrawModal'));
            document.getElementById('modal-shop-name').textContent = shopName;
            document.getElementById('modal-shop-id').value = shopId;
            document.getElementById('modal-balance').textContent = new Intl.NumberFormat('id-ID').format(balance);
            document.getElementById('withdrawal-amount').max = Math.floor(balance); // Optional: prevent entering more than balance HTML side
            modal.show();
        }
    </script>
@endsection