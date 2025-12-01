<?php

namespace App\Services\Mobile;

use App\Models\Product;
use App\Models\Store;

class SearchService
{
    public function search(string $search): array
    {
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

        return [
            'exact product name' => $productNameMatch,
            'exact store name' => $storeNameMatch,
            'similar product name' => $productNameSimilar,
            'similar store name' => $storeNameSimilar,
            'product description' => $productDescriptionSimilar,
            'store description' => $storeDescriptionSimilar,
        ];
    }
}
