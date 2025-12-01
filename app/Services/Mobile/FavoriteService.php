<?php

namespace App\Services\Mobile;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    public function getFavorites(): JsonResponse
    {
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
    }

    public function addToFavorite($id): JsonResponse
    {
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
    }

    public function deleteFromFavorite($id): JsonResponse
    {
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
    }
}
