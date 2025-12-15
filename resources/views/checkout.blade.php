@extends('layouts.app')

@section('head')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="fw-bold mb-0">Order Summary</h4>
                    </div>

                    <div class="card-body">
                        @foreach ($order->items as $item)
                            <div class="mb-3">
                                <strong>{{ $item->meal_name }}</strong>
                                × {{ $item->quantity }}

                                <div class="text-muted small">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </div>

                                @foreach ($item->options as $option)
                                    <div class="small text-muted ms-3">
                                        + {{ $option->option_name }}
                                        (Rp {{ number_format($option->price, 0, ',', '.') }})
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total</span>
                            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <button id="pay-button" class="btn btn-success w-100">
                    Pay Now
                </button>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            snap.pay("{{ $order->snap_token }}", {
                onSuccess: function () {
                },
                onPending: function () {
                    alert('Waiting for payment');
                },
                onError: function () {
                    alert('Payment failed');
                }
            });
        });
    </script>
@endsection