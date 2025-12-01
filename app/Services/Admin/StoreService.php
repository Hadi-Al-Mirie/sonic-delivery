<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\Store\StoreStoreRequest;
use App\Http\Requests\Admin\Store\UpdateStoreRequest;
use App\Models\Store;

class StoreService
{
    public function getStores()
    {
        return Store::orderBy('created_at', 'desc')->paginate(10);
    }

    public function getStoreWithProducts($id)
    {
        return Store::with('products')->findOrFail($id);
    }

    public function createStore(StoreStoreRequest $request): void
    {
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('store_logos', 'public');
        }

        Store::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'logo' => $logoPath ?? null,
        ]);
    }

    public function getStoreById($id)
    {
        return Store::findOrFail($id);
    }

    public function updateStore(UpdateStoreRequest $request, $id): void
    {
        $store = Store::findOrFail($id);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('store_logos', 'public');
        }

        $store->update([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'logo' => isset($logoPath) ? $logoPath : $store->logo,
        ]);
    }

    public function deleteStore($id): void
    {
        $store = Store::findOrFail($id);
        $store->delete();
    }
}