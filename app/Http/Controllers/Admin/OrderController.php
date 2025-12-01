<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UpdateOrderRequest;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request, OrderService $orderService)
    {
        $orders = $orderService->getOrders($request);
        return view('dashboard.orders.index', compact('orders'));
    }

    public function show($id, OrderService $orderService)
    {
        $order = $orderService->getOrderById($id);
        return view('dashboard.orders.show', compact('order'));
    }

    public function update(UpdateOrderRequest $request, $id, OrderService $orderService)
    {
        $orderService->updateOrderStatus($request, $id);
        return redirect()->route('admin.orders.index')
            ->with('success', 'Order status updated successfully.');
    }
}