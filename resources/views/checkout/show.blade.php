@extends('layouts.app')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->id }}</li>
        </ol>
    </nav>

    @php
        $statusClass = match ($order->status) {
            'Pending' => 'warning',
            'Shipped' => 'info',
            'Delivered' => 'success',
            default => 'secondary',
        };
    @endphp

    <h1 class="h3 mb-3">Order #{{ $order->id }}</h1>
    <p class="mb-4">Status: <span class="badge text-bg-{{ $statusClass }}">{{ $order->status }}</span></p>

    <div class="card card-body mb-4">
        <h5>Shipping Information</h5>
        <p class="mb-1">{{ $order->shipping_name }}</p>
        <p class="mb-1">{{ $order->shipping_email }} | {{ $order->shipping_phone }}</p>
        <p class="mb-0">{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_country }} {{ $order->shipping_postal_code }}</p>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Removed product' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format((float)$item->price, 2) }}</td>
                    <td>${{ number_format((float)$item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <h4 class="text-end">Total: ${{ number_format((float)$order->total_amount, 2) }}</h4>

    <div class="mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Back to order history</a>
    </div>
@endsection
