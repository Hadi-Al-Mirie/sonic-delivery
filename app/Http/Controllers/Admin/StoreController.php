<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\StoreStoreRequest;
use App\Http\Requests\Admin\Store\UpdateStoreRequest;
use App\Services\Admin\StoreService;

class StoreController extends Controller
{
    public function index(StoreService $storeService)
    {
        $stores = $storeService->getStores();

        return view('dashboard.stores.index', compact('stores'));
    }

    public function show($id, StoreService $storeService)
    {
        $store = $storeService->getStoreWithProducts($id);

        return view('dashboard.stores.show', compact('store'));
    }

    public function create()
    {
        return view('dashboard.stores.add');
    }

    public function store(StoreStoreRequest $request, StoreService $storeService)
    {
        $storeService->createStore($request);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Store created successfully.');
    }

    public function edit($id, StoreService $storeService)
    {
        $store = $storeService->getStoreById($id);

        return view('dashboard.stores.edit', compact('store'));
    }

    public function update(UpdateStoreRequest $request, $id, StoreService $storeService)
    {
        $storeService->updateStore($request, $id);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Store updated successfully.');
    }

    public function destroy($id, StoreService $storeService)
    {
        $storeService->deleteStore($id);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Store deleted successfully.');
    }
}