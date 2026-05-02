@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Products</h1>
    </div>

    <form class="row g-2 mb-4" method="GET" action="{{ route('products.index') }}">
        <div class="col-12 col-md-4">
            <select name="category" class="form-select">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <input type="number" step="0.01" name="min_price" class="form-control" placeholder="Min price" value="{{ request('min_price') }}">
        </div>
        <div class="col-6 col-md-3">
            <input type="number" step="0.01" name="max_price" class="form-control" placeholder="Max price" value="{{ request('max_price') }}">
        </div>
        <div class="col-12 col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="row g-3">
        @forelse($products as $product)
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100">
                    <img src="{{ \App\Support\ProductImage::forProduct($product->image, $product->id) }}" class="card-img-top" alt="{{ $product->name }}" loading="lazy" width="900" height="675">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="text-muted small">{{ $product->short_description }}</p>
                        <p class="fw-bold mb-3">${{ number_format((float)$product->price, 2) }}</p>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary mt-auto">View details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No products found.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection
