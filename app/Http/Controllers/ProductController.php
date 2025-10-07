<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;
class ProductController extends Controller
{
    public function getDetails($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json([
                'product' => $product
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
    public function getAllProducts()
    {
        try {
            $products = Product::all();
            return response()->json([
                'Products' => $products,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Products not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }
}
