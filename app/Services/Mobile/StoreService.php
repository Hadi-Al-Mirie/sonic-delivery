<?php

namespace App\Services\Mobile;

use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;

class StoreService
{
    public function getStoreProducts(int $id)
    {
        $store = Store::find($id);

        if (!$store) {
            return null;
        }

        return $store->products;
    }

    public function getAllStores(): Collection
    {
        return Store::all();
    }
}
