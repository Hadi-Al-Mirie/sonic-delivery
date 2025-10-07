<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\User;

class OrderController extends Controller
{
    public function getOrders()
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            return response()->json([
                'orders' => $user->orders()->get()
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Order not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }
    public function getItems($id)
    {
        try {
            $order = Order::findOrFail($id);
            $items = OrderItem::where('order_id', $order->id)->with('product')->get();
            $formItems = $items->map(function ($items) {
                return [
                    'product_id' => $items->product_id,
                    'quantity' => $items->quantity,
                    'price' => $items->price,
                    'description' => $items->product->name,
                    'image' => $items->product->image,
                    'store_id' => $items->product->store_id
                ];
            });
            return response()->json([
                'order' => [
                    "order_id" => $order->id,
                    "total price" => $order->total_price,
                    "status" => $order->status,
                    "items" => [
                        $formItems
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }
    public function addOrder()
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            if (!$user->location) {
                return response()->json([
                    'message' => 'Your Location field is empty, please fill your loacation and try again later',
                ], 400);
            }
            $carts = Cart::with('product')->where('user_id', $user->id)->get();
            if ($carts->isEmpty()) {
                return response()->json([
                    'message' => 'Your cart is empty.',
                ], 400);
            }
            $order = DB::transaction(function () use ($carts, $user) {
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
            return response()->json([
                'message' => 'Products processed successfully.',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'total_price' => $order->total_price,
                    'created_at' => $order->created_at,
                ],
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'A product in your cart was not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }


    public function cancelOrder($id)
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            $order = Order::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
            if (in_array($order->status, ['canceled', 'completed'])) {
                return response()->json([
                    'message' => 'Cannot cancel this order.',
                ], 400);
            }
            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
                $order->update(['status' => 'cancelled']);
            });
            return response()->json([
                'message' => 'Order canceled successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }
}