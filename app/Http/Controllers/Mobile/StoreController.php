<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Store;
use App\Http\Controllers\Controller;
use App\Services\Mobile\StoreService;

class StoreController extends Controller
{
    protected StoreService $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function getProducts($id)
    {
        $products = $this->storeService->getStoreProducts((int) $id);

        if ($products === null) {
            return response()->json([
                'error' => 'Store not found',
            ], 404);
        }

        return response()->json([
            'products' => $products,
        ]);
    }

    public function getAllStores()
    {
        $stores = $this->storeService->getAllStores();

        return response()->json([
            'stores' => $stores,
        ]);
    }
}