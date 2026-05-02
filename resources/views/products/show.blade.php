@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <img src="{{ \App\Support\ProductImage::forProduct($product->image, $product->id) }}" class="img-fluid rounded" alt="{{ $product->name }}" loading="eager" width="900" height="675">
            <div class="d-flex gap-2 mt-2">
                @php
                    $main = \App\Support\ProductImage::forProduct($product->image, $product->id);
                @endphp
                <img src="{{ $main }}" class="img-thumbnail" style="max-width: 120px;" alt="{{ $product->name }} thumbnail 1" loading="lazy" width="120" height="90">
                <img src="{{ \App\Support\ProductImage::galleryVariant($product->id, 1) }}" class="img-thumbnail" style="max-width: 120px;" alt="{{ $product->name }} thumbnail 2" loading="lazy" width="120" height="90">
                <img src="{{ \App\Support\ProductImage::galleryVariant($product->id, 2) }}" class="img-thumbnail" style="max-width: 120px;" alt="{{ $product->name }} thumbnail 3" loading="lazy" width="120" height="90">
            </div>
        </div>
        <div class="col-12 col-md-6">
            <h1 class="h3">{{ $product->name }}</h1>
            <p class="text-muted">{{ $product->category?->name ?? 'Uncategorized' }}</p>
            <p>{{ $product->description ?: $product->short_description }}</p>
            <h3 class="fw-bold mb-3">${{ number_format((float)$product->price, 2) }}</h3>
            <p>
                Stock status:
                @if($product->stock > 0)
                    <span class="badge text-bg-success">In stock ({{ $product->stock }})</span>
                @else
                    <span class="badge text-bg-danger">Out of stock</span>
                @endif
            </p>

            <form method="POST" action="{{ route('cart.store', $product) }}" class="row g-2 mt-2">
                @csrf
                <div class="col-4">
                    <input type="number" min="1" max="{{ max($product->stock, 1) }}" name="quantity" value="1" class="form-control" @disabled($product->stock < 1)>
                </div>
                <div class="col-8">
                    <button class="btn btn-primary w-100" @disabled($product->stock < 1)>Add to cart</button>
                </div>
            </form>
        </div>
    </div>
@endsection
