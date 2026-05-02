<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductService
{
    public function getFilteredProducts(Request $request, int $perPage = 9): LengthAwarePaginator
    {
        return $this->applyFilters(Product::query()->with('category'), $request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getFilteredProductsForApi(Request $request, int $perPage = 10): LengthAwarePaginator
    {
        return $this->applyFilters(Product::query()->with('category'), $request)
            ->latest()
            ->paginate($perPage);
    }

    public function getCategories(): Collection
    {
        return Category::query()->orderBy('name')->get();
    }

    public function getProductDetails(Product $product): Product
    {
        return $product->load('category');
    }

    private function applyFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        return $query;
    }
}
