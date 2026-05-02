<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;

class OrderApiController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function show(Order $order)
    {
        return response()->json($this->orderService->getAuthorizedOrderDetails($order, auth()->user()));
    }
}
