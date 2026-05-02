@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4">Checkout</h1>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <form method="POST" action="{{ route('checkout.store') }}" class="card card-body">
                @csrf
                <input type="hidden" name="idempotency_key" value="{{ $idempotencyKey }}">

                <h5 class="mb-3">Shipping Details</h5>

                <div class="mb-2">
                    <label class="form-label">Full Name</label>
                    <input class="form-control" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="shipping_email" value="{{ old('shipping_email', auth()->user()->email) }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Phone</label>
                    <input class="form-control" name="shipping_phone" value="{{ old('shipping_phone') }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Address</label>
                    <input class="form-control" name="shipping_address" value="{{ old('shipping_address') }}" required>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">City</label>
                        <input class="form-control" name="shipping_city" value="{{ old('shipping_city') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Country</label>
                        <input class="form-control" name="shipping_country" value="{{ old('shipping_country') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Postal Code</label>
                        <input class="form-control" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required>
                    </div>
                </div>

                <button class="btn btn-primary mt-3">Place Order</button>
            </form>
        </div>
        <div class="col-12 col-lg-5">
            <div class="card card-body">
                <h5>Order Summary</h5>
                <hr>
                @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between">
                        <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                        <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
