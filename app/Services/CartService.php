<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const CART_KEY = 'cart.items';

    public function items(): array
    {
        return Session::get(self::CART_KEY, []);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->items();
        $id = (string) $product->id;
        $existingQty = $cart[$id]['quantity'] ?? 0;

        $cart[$id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'image' => $product->image,
            'quantity' => min($existingQty + $quantity, $product->stock),
        ];

        Session::put(self::CART_KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->items();
        $key = (string) $productId;

        if (! isset($cart[$key])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$key]);
            Session::put(self::CART_KEY, $cart);

            return;
        }

        $product = Product::find($productId);
        if (! $product) {
            unset($cart[$key]);
            Session::put(self::CART_KEY, $cart);

            return;
        }

        $cart[$key]['quantity'] = min($quantity, $product->stock);
        Session::put(self::CART_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->items();
        unset($cart[(string) $productId]);
        Session::put(self::CART_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::CART_KEY);
    }

    public function total(): float
    {
        return array_reduce($this->items(), function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0.0);
    }
}
