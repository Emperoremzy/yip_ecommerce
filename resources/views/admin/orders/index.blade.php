@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-3">Admin Order Management</h1>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}<br><small>{{ $order->shipping_email }}</small></td>
                    <td>${{ number_format((float)$order->total_amount, 2) }}</td>
                    <td><span class="badge text-bg-secondary">{{ $order->status }}</span></td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm">
                                @foreach(['Pending', 'Shipped', 'Delivered'] as $status)
                                    <option value="{{ $status }}" @selected($status === $order->status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary">Save</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No orders yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
@endsection
