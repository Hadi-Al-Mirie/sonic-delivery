<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Order;
use App\Models\OrderItem;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    public function getOrders()
    {
        try {
            $user = Auth::user();
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
            return response()->json([
                'order items' => $order->items()->with('product')->get()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }
    public function addOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'products' => 'required|array|min:1',
                'products.*.id' => 'required|integer|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
            ]);
            $req_products = $validated['products'];
            $user = Auth::user();
            $order = DB::transaction(function () use ($req_products, $user) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'total_price' => 0,
                ]);
                $totalPrice = 0;
                foreach ($req_products as $req_product) {
                    $product = Product::where('id', $req_product['id'])->first();
                    $quantity = $req_product['quantity'];
                    if ($product->stock < $quantity) {
                        throw new \Exception(
                            "The requested quantityis not available for product:{$product->name}"
                        );
                    }
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                    ]);
                    $totalPrice += $product->price * $quantity;
                    $product->decrement('stock', $quantity);
                }
                $order->update(['total_price' => $totalPrice]);
                return $order;
            });
            return response()->json([
                'message' => 'Products processed successfully.',
                'order' => $order
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Product not found',
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
            $order = Order::findOrFail($id);
            if (in_array($order->status, ['canceled', 'completed'])) {
                return response()->json([
                    'message' => 'Cannot cancel this order.',
                ], 400);
            }
            $order->status = 'cancled';
            $order->save();
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
