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
            $favorites = $user->favorites()->get();
            return response()->json([
                'favorites' => $favorites,
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
                'new favorite' => $new_fav
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
