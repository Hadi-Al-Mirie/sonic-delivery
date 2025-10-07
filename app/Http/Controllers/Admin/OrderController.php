<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('created_at', 'desc');

        if ($request->has('order_id') && $request->order_id) {
            $query->where('id', $request->order_id);
        }

        $orders = $query->paginate(10);
        return view('dashboard.orders.index', compact('orders'));
    }


    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('dashboard.orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }
}
