<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AdminOrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderManagementController extends Controller
{
    public function __construct(private AdminOrderService $adminOrderService)
    {
    }

    public function index()
    {
        $orders = $this->adminOrderService->getPaginatedOrders(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(AdminOrderService::ALLOWED_STATUSES)],
        ]);

        $this->adminOrderService->updateStatus($order, $validated['status']);

        return back()->with('success', 'Order status updated.');
    }
}
