<?php

namespace App\Http\Controllers;
use App\Models\Store;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class StoreController extends Controller
{
    public function getProducts($id)
    {
        try {
            $store = Store::findOrFail($id);
            return response()->json([
                'products' => $store->products,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Store not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }
    public function getAllStores()
    {
        try {
            $stores = Store::all();
            return response()->json([
                'stores' => $stores,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Store not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }
}