<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        return response()->json($this->productService->getFilteredProductsForApi($request, 10));
    }

    public function show(Product $product)
    {
        return response()->json($this->productService->getProductDetails($product));
    }
}
