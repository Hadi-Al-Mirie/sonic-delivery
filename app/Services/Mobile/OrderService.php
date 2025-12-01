<?php

namespace App\Services\Mobile;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function getUserOrders(int $userId): ?Collection
    {
        $user = User::find($userId);

        if (!$user) {
            return null;
        }

        return $user->orders()->get();
    }

    public function getOrderWithItems(int $orderId): ?array
    {
        $order = Order::find($orderId);

        if (!$order) {
            return null;
        }

        $items = OrderItem::where('order_id', $order->id)
            ->with('product')
            ->get();

        $formItems = $items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'description' => $item->product->name,
                'image' => $item->product->image,
                'store_id' => $item->product->store_id,
            ];
        });

        return [
            'order_id' => $order->id,
            'total price' => $order->total_price,
            'status' => $order->status,
            'items' => [
                $formItems,
            ],
        ];
    }

    public function createOrder(User $user, Collection $carts): Order
    {
        return DB::transaction(function () use ($carts, $user) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_price' => 0,
            ]);

            $totalPrice = 0;

            foreach ($carts as $cart) {
                $product = $cart->product;
                $quantity = $cart->quantity;
                $price = $product->price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $totalPrice += $price;
            }

            $order->update(['total_price' => $totalPrice]);

            Cart::where('user_id', $user->id)->delete();

            return $order;
        });
    }

    public function cancelOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);

                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
            $order->update(['status' => 'cancelled']);
        });
    }
}
