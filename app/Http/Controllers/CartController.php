<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getCarts()
    {
        try {
            $user = Auth::user();
            //$carts = $user->carts()->get();
            $carts = $user->carts()->with('product')->get();
            return response()->json([
                'carts' => $carts,
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
            $user = Auth::user();
            $exist = Cart::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();
            if ($exist) {
                return response()->json([
                    'message' => 'This product is already in your Cart.',
                ], 200);
            }
            $cart = Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            return response()->json([
                "message" => "product was added to your cart successfully",
                "new cart item " => $cart
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
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();
            if (!$cart) {
                return response()->json([
                    'message' => 'This product is not in your Cart.',
                ], 404);
            }
            $cart->delete();
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
}
