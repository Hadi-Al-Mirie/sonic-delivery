<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Services\Mobile\ProductService;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getDetails($id)
    {
        $product = $this->productService->getProductById((int) $id);

        if (!$product) {
            return response()->json([
                'error' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'product' => $product,
        ], 200);
    }

    public function getAllProducts()
    {
        $products = $this->productService->getAllProducts();

        return response()->json([
            'Products' => $products,
        ]);
    }
}