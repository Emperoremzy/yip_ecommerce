<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        $products = $this->productService->getFilteredProducts($request, 9);
        $categories = $this->productService->getCategories();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product = $this->productService->getProductDetails($product);

        return view('products.show', compact('product'));
    }
}
