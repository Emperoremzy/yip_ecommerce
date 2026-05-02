<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CartService $cartService)
    {
        return view('cart.index', [
            'cartItems' => $cartService->items(),
            'total' => $cartService->total(),
        ]);
    }

    public function store(Request $request, Product $product, CartService $cartService)
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        if ($product->stock <= 0) {
            return back()->withErrors(['cart' => 'This product is out of stock.']);
        }

        $cartService->add($product, $validated['quantity'] ?? 1);

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, Product $product, CartService $cartService)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cartService->update($product->id, (int) $validated['quantity']);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function destroy(Product $product, CartService $cartService)
    {
        $cartService->remove($product->id);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
