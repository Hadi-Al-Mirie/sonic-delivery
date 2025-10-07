<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class FavoriteController extends Controller
{
    public function getFavorites()
    {
        try {
            $user = Auth::user();
            $favorites = Favorite::where('user_id', $user->id)->with('product')->get();
            $formFav = $favorites->map(function ($favorite) {
                return [
                    'favorite_id' => $favorite->id,
                    'user_id' => $favorite->user_id,
                    'product' => [
                        'id' => $favorite->product->id,
                        'name' => $favorite->product->name,
                        'price' => $favorite->product->price,
                        'image' => $favorite->product->image,
                        'stock' => $favorite->product->stock,
                    ],
                ];
            });
            return response()->json([
                'favorites' => $formFav,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }

    public function addToFavorite($id)
    {
        try {
            $product = Product::findOrFail($id);
            $user = Auth::user();
            $exist = Favorite::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();
            if ($exist) {
                return response()->json([
                    'message' => 'This product is already in your favorites.',
                ], 200);
            }
            $new_fav = Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            return response()->json([
                'favorite' => [
                    "id" => $new_fav->id,
                    "user_id" => $user->id,
                    "product_id" => $product->id,
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

    public function deleteFromFavorite($id)
    {
        try {
            $product = Product::findOrFail($id);
            $user = Auth::user();
            $fav_prod = Favorite::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();
            if (!$fav_prod) {
                return response()->json([
                    'message' => 'This product is not is your favorites.',
                ], 404);
            }
            $fav_prod->delete();
            return response()->json([
                'message' => 'Product removed from favorites successfully.',
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
