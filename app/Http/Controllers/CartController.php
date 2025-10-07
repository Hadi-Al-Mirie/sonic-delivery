<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class CartController extends Controller
{
    public function getCarts()
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            $carts = Cart::where('user_id', $user->id)->with('product')->get();
            $formCart = $carts->map(function ($carts) {
                return [
                    'cart_id' => $carts->id,
                    'user_id' => $carts->user_id,
                    'quantity' => $carts->quantity,
                    'product' => [
                        'id' => $carts->product->id,
                        'name' => $carts->product->name,
                        'description' => $carts->product->description,
                        'price' => $carts->product->price,
                        'image' => $carts->product->image,
                        'stock' => $carts->product->stock,
                        'store_id' => $carts->product->store_id
                    ],
                ];
            });
            return response()->json([
                'carts' => $formCart,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }
    public function addToCart($id)
    {
        try {
            $product = Product::findOrFail($id);
            $user = User::findOrFail(Auth::user()->id);
            if ($product->stock <= 0) {
                return response()->json([
                    'message' => 'This product is out of stock.',
                ], 400);
            }
            $exist = Cart::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();
            if ($exist) {
                return response()->json([
                    'message' => 'This product is already in your Cart.',
                ], 200);
            }
            $cart = DB::transaction(function () use ($product, $user) {
                $cart = Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
                $product->decrement('stock', 1);
                return $cart;
            });
            return response()->json([
                "message" => "product was added to your cart successfully",
                "new cart" => [
                    "cart_id" => $cart->id,
                    "product_id" => $product->id,
                    "user_id" => $user->id
                ]
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

    public function deleteFromCart($id)
    {
        try {
            $product = Product::findOrFail($id);
            $user = User::findOrFail(Auth::user()->id);
            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();
            if (!$cart) {
                return response()->json([
                    'message' => 'This product is not in your Cart.',
                ], 404);
            }
            DB::transaction(function () use ($product, $cart) {
                $product->increment('stock', $cart->quantity);
                $cart->delete();
            });
            return response()->json([
                "message" => "product was deleted from your cart successfully",
            ], 200);
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

    public function incrementQuantity($id)
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            $product = Product::findOrFail($id);
            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();
            if (!$cart) {
                return response()->json([
                    "message" => "This product is not in your cart"
                ], 404);
            }
            if ($product->stock < 1) {
                return response()->json([
                    "message" => "There is no more quantity available"
                ], 400);
            }
            DB::transaction(function () use ($product, $cart) {
                $product->decrement('stock', 1);
                $cart->increment('quantity', 1);
            });
            return response()->json([
                "message" => "quantity added successfully",
            ], 200);
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
    public function decrementQuantity($id)
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            $product = Product::findOrFail($id);
            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->firstOrFail();
            if ($cart->quantity <= 1) {
                return response()->json([
                    'message' => 'Cannot reduce quantity below 1 , remove the item from the cart instead.',
                ], 400);
            }
            DB::transaction(function () use ($product, $cart) {
                $product->increment('stock', 1);
                $cart->decrement('quantity', 1);
            });
            return response()->json([
                'message' => 'Quantity reduced successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product or cart item not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}