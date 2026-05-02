@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
        <h1 class="h3 mb-0">My orders</h1>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">Continue shopping</a>
    </div>

    @if($orders->total() === 0)
        <div class="alert alert-info">
            You have not placed any orders yet.
            <a href="{{ route('products.index') }}">Browse products</a>.
        </div>
    @else
        <p class="text-muted small mb-3">Track the status of each order. Click “View details” for the full breakdown.</p>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    @php
                        $statusClass = match ($order->status) {
                            'Pending' => 'warning',
                            'Shipped' => 'info',
                            'Delivered' => 'success',
                            default => 'secondary',
                        };
                    @endphp
                    <tr>
                        <td class="fw-medium">#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('M j, Y g:i A') }}</td>
                        <td>{{ $order->items_count }}</td>
                        <td>${{ number_format((float) $order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge text-bg-{{ $statusClass }}">{{ $order->status }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">View details</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
