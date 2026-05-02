<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminOrderService
{
    public const ALLOWED_STATUSES = ['Pending', 'Shipped', 'Delivered'];

    public function getPaginatedOrders(int $perPage = 15): LengthAwarePaginator
    {
        return Order::with('user')->latest()->paginate($perPage);
    }

    public function updateStatus(Order $order, string $status): void
    {
        $order->update(['status' => $status]);
    }
}
