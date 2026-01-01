@extends('layouts.app')

@section('content')
    {{-- Keep the same CSS for consistency --}}
    <style>
        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            background-color: #F97352 !important;
            color: white !important;
        }

        .nav-pills .nav-link {
            color: #555;
            font-weight: 500;
        }

        .nav-pills .nav-link:hover {
            background-color: #fff9f7;
            color: #F97352;
        }

        .shop-order-card {
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }

        .shop-order-card:hover {
            background-color: #fcfcfc;
            border-left: 4px solid #F97352;
        }

        /* Custom button style to match theme */
        .btn-theme {
            background-color: #F97352;
            color: white;
            border: none;
        }

        .btn-theme:hover {
            background-color: #e06546;
            color: white;
        }
    </style>

    <div class="container py-5">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Shop Dashboard Header --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        @if($shop->profileImage)
                            <img src="{{ asset('storage/' . $shop->profileImage) }}" class="rounded-circle me-3" width="60"
                                height="60" style="object-fit: cover; border: 2px solid #F97352;">
                        @else
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold"
                                style="width: 60px; height: 60px; background-color: #ccc; font-size: 1.5rem;">
                                {{ substr($shop->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="mb-0 fw-bold">{{ $shop->name }}</h4>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge {{ $shop->status === 'OPEN' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $shop->status }}
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-wallet2"></i> Balance:
                                    Rp {{ number_format($shop->wallet->balance ?? 0, 0, ',', '.') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- THE REQUESTED SINGLE BUTTON --}}
                    <div>
                        <button class="btn btn-theme rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                            <i class="bi bi-qr-code-scan"></i>
                            <span>Update Status (Scan)</span>
                        </button>
                    </div>
                </div>

                {{-- Main Card --}}
                <div class="card shadow-sm border-0 overflow-hidden" style="min-height: 600px;">

                    {{-- Tabs --}}
                    <div class="card-header bg-white border-bottom pt-4 pb-0">
                        <ul class="nav nav-pills card-header-pills pb-3" id="orders-tab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active rounded-pill px-4 me-2" id="pills-all-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab">
                                    Incoming
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill px-4 me-2" id="pills-pending-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-pending" type="button" role="tab">
                                    Pending
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill px-4 me-2" id="pills-cooking-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-cooking" type="button" role="tab">
                                    <i class="bi bi-fire me-1"></i> Cooking
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill px-4 me-2" id="pills-ready-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-ready" type="button" role="tab">
                                    <i class="bi bi-bell me-1"></i> Ready
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill px-4" id="pills-completed-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-completed" type="button" role="tab">
                                    Completed
                                </button>
                            </li>
                        </ul>
                    </div>

                    {{-- Body Content --}}
                    <div class="card-body bg-light p-0">
                        <div class="tab-content" id="pills-tabContent">

                            {{-- 1. INCOMING (Awaiting Payment) --}}
                            <div class="tab-pane fade show active" id="pills-all" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($pendingPayOrder as $order)
                                        @include('shopOrders.order-card-staff', ['order' => $order])
                                    @empty
                                        <div class="text-center py-5">
                                            <p class="text-muted">No incoming orders.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 2. PENDING (Paid, Awaiting Confirmation) --}}
                            <div class="tab-pane fade" id="pills-pending" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($pendingOrder as $order)
                                        @include('shopOrders.order-card-staff', ['order' => $order])
                                    @empty
                                        <div class="text-center py-5">
                                            <p class="text-muted">No pending orders.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 3. COOKING --}}
                            <div class="tab-pane fade" id="pills-cooking" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($confirmedOrder as $order)
                                        @include('shopOrders.order-card-staff', ['order' => $order])
                                    @empty
                                        <div class="text-center py-5">
                                            <p class="text-muted">Kitchen is clear.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 4. READY --}}
                            <div class="tab-pane fade" id="pills-ready" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($readyOrder as $order)
                                        @include('shopOrders.order-card-staff', ['order' => $order])
                                    @empty
                                        <div class="text-center py-5">
                                            <p class="text-muted">No orders waiting for pickup.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 5. COMPLETED --}}
                            <div class="tab-pane fade" id="pills-completed" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($completedOrder as $order)
                                        @include('shopOrders.order-card-staff', ['order' => $order])
                                    @empty
                                        <div class="text-center py-5">
                                            <p class="text-muted">No history yet.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCANNER MODAL --}}
    <div class="modal fade" id="qrScanModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Scan Customer QR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center position-relative">

                    {{-- Camera View --}}
                    <div id="scanner-container" class="position-relative overflow-hidden rounded-3"
                        style="width: 100%; min-height: 300px; background: #000;">
                        <div id="reader" style="width: 100%; height: 100%;"></div>

                        {{-- Swap Camera Button --}}
                        <button id="swapCameraBtn"
                            class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle shadow p-2"
                            style="z-index: 10; width: 40px; height: 40px; display: none;">
                            <i class="bi bi-arrow-repeat fs-5"></i>
                        </button>
                    </div>

                    {{-- Scan Status Text --}}
                    <div id="scan-status" class="mt-3">
                        <p class="text-muted small mb-0">Point camera at customer's order QR code</p>
                    </div>

                    {{-- Confirmation / Result View (Hidden by default) --}}
                    <div id="scan-result" class="d-none mt-3 p-3 bg-light rounded text-start">
                        <div class="text-center mb-3">
                            <div id="result-icon" class="fs-1 text-primary mb-2">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <h5 id="result-title" class="fw-bold">Checking Order...</h5>
                            <p id="result-message" class="text-muted small mb-0">Please wait.</p>
                        </div>

                        <div id="order-details" class="d-none border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Customer:</span>
                                <span class="fw-bold" id="detail-customer">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total:</span>
                                <span class="fw-bold" id="detail-total">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Current Status:</span>
                                <span class="badge bg-secondary" id="detail-status">-</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button id="btn-confirm-update" class="btn btn-success d-none">
                                <i class="bi bi-check-circle me-2"></i> Update to Completed
                            </button>
                            <button id="btn-resume-scan" class="btn btn-outline-secondary">
                                <i class="bi bi-qr-code-scan me-2"></i> Scan Next
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const html5QrCode = new Html5Qrcode("reader");
            let isScanning = false;
            let currentCameraId = null;
            let cameras = [];

            // Elements
            const modalEl = document.getElementById('qrScanModal');
            const scannerContainer = document.getElementById('scanner-container');
            const scanStatus = document.getElementById('scan-status');
            const scanResult = document.getElementById('scan-result');
            const swapBtn = document.getElementById('swapCameraBtn');

            const resultIcon = document.getElementById('result-icon');
            const resultTitle = document.getElementById('result-title');
            const resultMessage = document.getElementById('result-message');
            const orderDetails = document.getElementById('order-details');

            const btnConfirm = document.getElementById('btn-confirm-update');
            const btnResume = document.getElementById('btn-resume-scan');

            let scannedOrderId = null;

            // Initialize Open Modal
            const scanBtn = document.querySelector('.btn-theme');
            if (scanBtn) {
                scanBtn.setAttribute('data-bs-toggle', 'modal');
                scanBtn.setAttribute('data-bs-target', '#qrScanModal');

                modalEl.addEventListener('shown.bs.modal', function () {
                    startScanner();
                });

                modalEl.addEventListener('hidden.bs.modal', function () {
                    stopScanner();
                    resetUI();
                });
            }

            // Swap Camera
            swapBtn.addEventListener('click', function () {
                if (cameras.length > 1 && isScanning) {
                    const currentIdx = cameras.findIndex(c => c.id === currentCameraId);
                    const nextIdx = (currentIdx + 1) % cameras.length;
                    const nextCameraId = cameras[nextIdx].id;

                    stopScanner().then(() => {
                        startScanner(nextCameraId);
                    });
                }
            });

            // Resume Scanning
            btnResume.addEventListener('click', function () {
                resetUI();
                html5QrCode.resume();
                isScanning = true;
            });

            // Confirm Update
            btnConfirm.addEventListener('click', function () {
                if (!scannedOrderId) return;

                // Show loading on button
                const OriginalText = btnConfirm.innerHTML;
                btnConfirm.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
                btnConfirm.disabled = true;

                fetch('{{ route("orders.scan-completion") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_id: scannedOrderId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showResult('success', 'Success!', data.message);
                            btnConfirm.classList.add('d-none');
                            // Optional: Auto-resume or keep showing success?
                            // Let's keep showing success and user can click "Scan Next"
                        } else {
                            showResult('error', 'Update Failed', data.message);
                            btnConfirm.innerHTML = OriginalText;
                            btnConfirm.disabled = false;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showResult('error', 'Error', 'Failed to connect to server.');
                        btnConfirm.innerHTML = OriginalText;
                        btnConfirm.disabled = false;
                    });
            });


            function startScanner(forceCameraId = null) {
                const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                // If we already have cameras, just start
                if (cameras.length > 0) {
                    let cameraId = forceCameraId;
                    if (!cameraId) {
                        // Default to back camera (environment) if available
                        const backCam = cameras.find(c => c.label.toLowerCase().includes('back') || c.label.toLowerCase().includes('environment'));
                        cameraId = backCam ? backCam.id : cameras[0].id;
                    }
                    currentCameraId = cameraId;

                    html5QrCode.start(cameraId, config, onScanSuccess, onScanFailure)
                        .then(() => {
                            isScanning = true;
                            // Show swap button if multiple cameras
                            if (cameras.length > 1) swapBtn.style.display = 'block';
                        })
                        .catch(err => {
                            console.error("Error starting scanner", err);
                            scanStatus.innerHTML = '<p class="text-danger">Camera error. Please allow permissions.</p>';
                        });
                    return;
                }

                // Initial fetch of cameras
                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        cameras = devices;
                        startScanner(forceCameraId); // recursive call now that we have cameras
                    } else {
                        scanStatus.innerHTML = '<p class="text-danger">No cameras found.</p>';
                    }
                }).catch(err => {
                    console.error("Error getting cameras", err);
                    scanStatus.innerHTML = '<p class="text-danger">Error accessing camera.</p>';
                });
            }

            function stopScanner() {
                if (isScanning) {
                    return html5QrCode.stop().then(() => {
                        isScanning = false;
                        swapBtn.style.display = 'none';
                    }).catch(err => console.log("Stop failed", err));
                }
                return Promise.resolve();
            }

            function onScanSuccess(decodedText, decodedResult) {
                // Pause scanner
                html5QrCode.pause();
                isScanning = false;

                console.log(`Scan result: ${decodedText}`);
                checkOrder(decodedText);
            }

            function onScanFailure(error) {
                // handle scan failure, usually better to ignore and keep scanning.
            }

            function checkOrder(orderId) {
                scannedOrderId = orderId;

                // Show analyzing UI
                scannerContainer.classList.add('d-none');
                scanStatus.classList.add('d-none');
                scanResult.classList.remove('d-none');

                showResult('info', 'Checking...', 'Verifying order details.');

                fetch('{{ route("orders.scan-check") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_id: orderId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const order = data.order;

                            // Fill details
                            document.getElementById('detail-customer').textContent = order.customer_name;
                            document.getElementById('detail-total').textContent = 'Rp ' + order.total_price;

                            const statusBadge = document.getElementById('detail-status');
                            statusBadge.textContent = order.status;
                            statusBadge.className = 'badge ' + (order.status === 'COMPLETED' ? 'bg-success' : 'bg-warning');

                            orderDetails.classList.remove('d-none');

                            if (order.status === 'COMPLETED') {
                                showResult('success', 'Already Completed', 'This order has already been picked up.');
                                btnConfirm.classList.add('d-none');
                            } else if (order.payment_status !== 'PAID') {
                                showResult('warning', 'Payment Pending', 'This order has not been paid yet.');
                                btnConfirm.classList.add('d-none');
                            } else {
                                showResult('question', 'Confirm Update?', 'Mark this order as COMPLETED?');
                                btnConfirm.classList.remove('d-none');
                                btnConfirm.disabled = false;
                            }
                        } else {
                            showResult('error', 'Invalid Order', data.message);
                            orderDetails.classList.add('d-none');
                            btnConfirm.classList.add('d-none');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showResult('error', 'Error', 'Connection failed.');
                        orderDetails.classList.add('d-none');
                        btnConfirm.classList.add('d-none');
                    });
            }

            function showResult(type, title, message) {
                resultTitle.textContent = title;
                resultMessage.textContent = message;

                let iconClass = 'bi-info-circle text-primary';
                if (type === 'success') iconClass = 'bi-check-circle-fill text-success';
                if (type === 'error') iconClass = 'bi-x-circle-fill text-danger';
                if (type === 'warning') iconClass = 'bi-exclamation-triangle-fill text-warning';
                if (type === 'question') iconClass = 'bi-question-circle-fill text-primary';

                resultIcon.innerHTML = `<i class="bi ${iconClass}"></i>`;
            }

            function resetUI() {
                scannerContainer.classList.remove('d-none');
                scanStatus.classList.remove('d-none');
                scanResult.classList.add('d-none');
                orderDetails.classList.add('d-none');
                scannedOrderId = null;
            }

        });
    </script>
@endsection