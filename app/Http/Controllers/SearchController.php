<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class SearchController extends Controller
{

    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'search' => 'required|string|max:255'
            ]);
            $search = strtolower($request->input('search'));
            $productNameMatch = Product::whereRaw('LOWER(name) = ?', [$search])->get();
            $storeNameMatch = Store::whereRaw('LOWER(name) = ?', [$search])->get();
            $productNameSimilar = Product::whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                ->whereRaw('LOWER(name) != ?', [$search])
                ->get();
            $storeNameSimilar = Store::whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                ->whereRaw('LOWER(name) != ?', [$search])
                ->get();
            $productDescriptionSimilar = Product::whereRaw('LOWER(description) LIKE ?', ["%{$search}%"])
                ->get();
            $storeDescriptionSimilar = Store::whereRaw('LOWER(description) LIKE ?', ["%{$search}%"])
                ->get();
            return response()->json([
                'exact product name' => $productNameMatch,
                'exact store name' => $storeNameMatch,
                'similar product name' => $productNameSimilar,
                'similar store name' => $storeNameSimilar,
                'product description' => $productDescriptionSimilar,
                'store description' => $storeDescriptionSimilar,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }
}













/*

$products = Product::where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            })
                ->select('products.*')
                ->selectRaw("
        CASE
            WHEN name LIKE ? THEN 1
            WHEN description LIKE ? THEN 2
            WHEN name LIKE ? THEN 3
            WHEN description LIKE ? THEN 4
            ELSE 5
        END as relevance
    ", ["{$search}", "{$search}", "{$search}%", "{$search}%"])
                ->orderBy('relevance')
                ->get();
            $stores = Store::where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            })
                ->select('stores.*')
                ->selectRaw("
        CASE
            WHEN name LIKE ? THEN 1
            WHEN description LIKE ? THEN 2
            WHEN name LIKE ? THEN 3
            WHEN description LIKE ? THEN 4
            ELSE 5
        END as relevance
    ", ["{$search}", "{$search}", "{$search}%", "{$search}%"])
                ->orderBy('relevance')
                ->get();
            return response()->json([
                'products' => $products,
                'stores' => $stores,
            ], 200);

*/
