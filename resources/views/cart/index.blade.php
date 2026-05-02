@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4">Shopping Cart</h1>

    @if(empty($cartItems))
        <div class="alert alert-info">
            Your cart is empty. <a href="{{ route('products.index') }}">Browse products</a>.
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th style="width: 160px;">Quantity</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>${{ number_format($item['price'], 2) }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.update', $item['product_id']) }}" class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <input class="form-control form-control-sm" type="number" min="0" name="quantity" value="{{ $item['quantity'] }}">
                                <button class="btn btn-sm btn-outline-secondary">Update</button>
                            </form>
                        </td>
                        <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.destroy', $item['product_id']) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <h4>Total: ${{ number_format($total, 2) }}</h4>
            <a class="btn btn-success" href="{{ route('checkout.create') }}">Proceed to checkout</a>
        </div>
    @endif
@endsection
