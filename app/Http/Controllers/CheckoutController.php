<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->paginatedOrdersForCustomer($request->user(), 12);

        return view('orders.index', compact('orders'));
    }

    public function create(CartService $cartService)
    {
        if (empty($cartService->items())) {
            return redirect()->route('products.index')->withErrors(['cart' => 'Your cart is empty.']);
        }

        return view('checkout.create', [
            'cartItems' => $cartService->items(),
            'total' => $cartService->total(),
            'idempotencyKey' => (string) Str::uuid(),
        ]);
    }

    public function store(Request $request, CartService $cartService)
    {
        $validated = $request->validate([
            'idempotency_key' => ['required', 'uuid'],
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_email' => ['required', 'email', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:50'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_country' => ['required', 'string', 'max:100'],
            'shipping_postal_code' => ['required', 'string', 'max:20'],
        ]);

        $cartItems = $cartService->items();
        if (empty($cartItems)) {
            return redirect()->route('products.index')->withErrors(['cart' => 'Your cart is empty.']);
        }

        try {
            $order = $this->orderService->placeOrder($request->user(), $validated, $cartItems);
        } catch (\RuntimeException $exception) {
            report($exception);

            return back()->withErrors(['checkout' => $exception->getMessage() ?: 'Order could not be placed.'])->withInput();
        }

        $cartService->clear();

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully.');
    }

    public function show(Order $order)
    {
        $order = $this->orderService->getAuthorizedOrderDetails($order, auth()->user());

        return view('checkout.show', compact('order'));
    }
}
