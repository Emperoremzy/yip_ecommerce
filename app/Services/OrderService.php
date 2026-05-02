<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function placeOrder(User $user, array $validated, array $cartItems): Order
    {
        try {
            return DB::transaction(function () use ($user, $validated, $cartItems) {
                $existingOrder = Order::where('idempotency_key', $validated['idempotency_key'])->first();
                if ($existingOrder) {
                    return $existingOrder;
                }

                $total = 0;
                $lineItems = [];

                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);
                    if (! $product || $product->stock < $item['quantity']) {
                        throw new \RuntimeException("Insufficient stock for {$item['name']}.");
                    }

                    $lineTotal = ((float) $product->price) * $item['quantity'];
                    $total += $lineTotal;

                    $lineItems[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'price' => (float) $product->price,
                    ];
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'idempotency_key' => $validated['idempotency_key'],
                    'status' => 'Pending',
                    'shipping_name' => $validated['shipping_name'],
                    'shipping_email' => $validated['shipping_email'],
                    'shipping_phone' => $validated['shipping_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'shipping_city' => $validated['shipping_city'],
                    'shipping_country' => $validated['shipping_country'],
                    'shipping_postal_code' => $validated['shipping_postal_code'],
                    'total_amount' => $total,
                ]);

                foreach ($lineItems as $lineItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $lineItem['product']->id,
                        'quantity' => $lineItem['quantity'],
                        'price' => $lineItem['price'],
                    ]);

                    $lineItem['product']->decrement('stock', $lineItem['quantity']);
                }

                return $order;
            });
        } catch (QueryException $exception) {
            $existingOrder = Order::where('idempotency_key', $validated['idempotency_key'])->first();
            if ($existingOrder) {
                return $existingOrder;
            }

            report($exception);
            throw new \RuntimeException('Order could not be placed. Please try again.');
        }
    }

    public function getAuthorizedOrderDetails(Order $order, User $user): Order
    {
        abort_unless($order->user_id === $user->id || $user->is_admin, 403);

        return $order->load('items.product');
    }

    public function paginatedOrdersForCustomer(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Order::query()
            ->where('user_id', $user->id)
            ->withCount('items')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}
