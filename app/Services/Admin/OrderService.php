<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\Order\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderService
{
    public function getOrders(Request $request)
    {
        $query = Order::with('user')->orderBy('created_at', 'desc');
        if ($request->has('order_id') && $request->order_id) {
            $query->where('id', $request->order_id);
        }
        return $query->paginate(10);
    }

    public function getOrderById($id)
    {
        return Order::with(['user', 'items.product'])->findOrFail($id);
    }

    public function updateOrderStatus(UpdateOrderRequest $request, $id): void
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
    }
}
