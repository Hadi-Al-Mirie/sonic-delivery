<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Services\Mobile\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function getOrders()
    {
        $userId = Auth::id();

        $orders = $this->orderService->getUserOrders($userId);

        if ($orders === null) {
            return response()->json([
                'error' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'orders' => $orders,
        ], 200);
    }

    public function getItems($id)
    {
        $orderData = $this->orderService->getOrderWithItems((int) $id);

        if ($orderData === null) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => 'Order not found.',
            ], 500);
        }

        return response()->json([
            'order' => $orderData,
        ], 200);
    }

    public function addOrder()
    {
        $userId = Auth::id();
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'message' => 'A product in your cart was not found.',
            ], 404);
        }

        if (!$user->location) {
            return response()->json([
                'message' => 'Your Location field is empty, please fill your loacation and try again later',
            ], 400);
        }

        $carts = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($carts->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty.',
            ], 400);
        }

        $order = $this->orderService->createOrder($user, $carts);

        return response()->json([
            'message' => 'Products processed successfully.',
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'total_price' => $order->total_price,
                'created_at' => $order->created_at,
            ],
        ], 201);
    }

    public function cancelOrder($id)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        if (in_array($order->status, ['canceled', 'completed'])) {
            return response()->json([
                'message' => 'Cannot cancel this order.',
            ], 400);
        }

        $this->orderService->cancelOrder($order);

        return response()->json([
            'message' => 'Order canceled successfully.',
        ], 200);
    }
}
