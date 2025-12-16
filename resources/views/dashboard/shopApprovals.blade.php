@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="/css/shopApprovals.css">

    <div class="container py-5">

        {{-- Header + Filter Buttons --}}
        <div class="d-flex align-items-center justify-content-between mb-5">
            <h3 class="fw-bold m-0" style="color: #333;">
                Shop Requests
            </h3>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.shopApprovals', ['filter' => 'pending']) }}"
                    class="btn btn-sm {{ request('filter', 'pending') === 'pending' ? 'btn-theme' : 'btn-outline-theme' }}">
                    Pending ({{ $pendingShops->count() }})
                </a>
                <a href="{{ route('admin.shopApprovals', ['filter' => 'active']) }}"
                    class="btn btn-sm {{ request('filter') === 'active' ? 'btn-theme' : 'btn-outline-theme' }}">
                    Active ({{ $activeShops->count() }})
                </a>
                <a href="{{ route('admin.shopApprovals', ['filter' => 'rejected']) }}"
                    class="btn btn-sm {{ request('filter') === 'rejected' ? 'btn-theme' : 'btn-outline-theme' }}">
                    Rejected ({{ $rejectedShops->count() }})
                </a>
                <a href="{{ route('admin.shopApprovals', ['filter' => 'suspended']) }}"
                    class="btn btn-sm {{ request('filter') === 'suspended' ? 'btn-theme' : 'btn-outline-theme' }}">
                    Suspended ({{ $suspendedShops->count() }})
                </a>
            </div>
        </div>

        @php
            $filter = request('filter', 'pending');
            $shopsToShow = match ($filter) {
                'active' => $activeShops,
                'rejected' => $rejectedShops,
                'suspended' => $suspendedShops,
                default => $pendingShops,
            };
        @endphp

        @if($shopsToShow->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3" style="font-size: 3rem; opacity: 0.2;">
                    <i class="bi bi-shop me-2"></i>
                </div>
                <h5 class="text-muted">No {{ ucfirst($filter) }} shops</h5>
                <p class="small text-muted">Shop registrations will appear here.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($shopsToShow as $shop)
                    <div class="col-md-6 col-lg-4">
                        <div class="shop-card h-100 d-flex flex-column">

                            {{-- Image Area --}}
                            <div class="img-wrapper">
                                <img src="{{ asset('storage/' . $shop->profileImage) }}" class="shop-img" alt="{{ $shop->name }}">
                                <span class="status-badge">
                                    {{ strtoupper($shop->status) }}
                                </span>
                            </div>

                            {{-- Content Area --}}
                            <div class="p-4 flex-grow-1">
                                <h5 class="card-title fw-bold mb-2">{{ $shop->name }}</h5>

                                <div class="d-flex align-items-center mb-2">
                                    <span class="info-icon">📍</span>
                                    <span class="text-muted small text-truncate">{{ $shop->address }}</span>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <span class="info-icon">📞</span>
                                    <span class="text-muted small">{{ $shop->phone }}</span>
                                </div>
                            </div>

                            {{-- Action Area (buttons are functional as before) --}}
                            <div class="p-3 pt-0 mt-auto">
                                <form action="{{ route('shops.accept', $shop->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" {{ $shop->status == "OPEN" || $shop->status == "CLOSE" ? 'disabled' : '' }}
                                        class="btn btn-theme w-100 py-2">
                                        Accept Request
                                    </button>
                                </form>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <button type="button" 
                                            onclick="openActionModal('{{ route('shops.decline', ['shop' => $shop->id, 'message' => 'PLACEHOLDER']) }}', 'Decline {{ $shop->name }}')"
                                            class="btn btn-outline-theme w-100 btn-sm"
                                            {{ $shop->status == "REJECTED" || $shop->status == "OPEN" || $shop->status == "CLOSE" ? 'disabled' : '' }}>
                                            Decline
                                        </button>
                                    </div>

                                    <div class="col-6">
                                        <button type="button" 
                                            onclick="openActionModal('{{ route('shops.suspend', ['shop' => $shop->id, 'message' => 'PLACEHOLDER']) }}', 'Suspend {{ $shop->name }}')"
                                            class="btn btn-outline-theme w-100 btn-sm"
                                            {{ $shop->status == "REJECTED" || $shop->status == "SUSPENDED" ? 'disabled' : '' }}>
                                            Suspend
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    {{-- Hidden Form for Submitting Actions --}}
    <form id="actionForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    {{-- Action Reason Modal --}}
    <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="reasonModalTitle">Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-2">Please provide a reason for this action:</p>
                    <textarea id="reasonInput" class="form-control" rows="4" placeholder="Enter reason here..."></textarea>
                    <div id="reasonError" class="text-danger small mt-1" style="display: none;">Reason is required.</div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-theme px-4" onclick="submitAction()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let targetUrlTemplate = '';

        function openActionModal(urlTemplate, title) {
            targetUrlTemplate = urlTemplate;
            document.getElementById('reasonModalTitle').innerText = title;
            document.getElementById('reasonInput').value = ''; 
            document.getElementById('reasonError').style.display = 'none';
            
            new bootstrap.Modal(document.getElementById('reasonModal')).show();
        }

        function submitAction() {
            const reasonInput = document.getElementById('reasonInput');
            const reason = reasonInput.value.trim();
            
            if (!reason) {
                reasonInput.classList.add('is-invalid');
                document.getElementById('reasonError').style.display = 'block';
                return;
            }

            reasonInput.classList.remove('is-invalid');
            
            // Replace PLACEHOLDER with encoded reason
            // The route parameter is 'message'
            const finalUrl = targetUrlTemplate.replace('PLACEHOLDER', encodeURIComponent(reason));
            
            const form = document.getElementById('actionForm');
            form.action = finalUrl;
            form.submit();
        }
    </script>
@endsection