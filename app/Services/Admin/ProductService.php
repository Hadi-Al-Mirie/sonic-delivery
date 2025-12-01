<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductService
{
    public function getIndexData(Request $request): array
    {
        $query = Product::with('store')->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }
        $products = $query->paginate(10);
        $stores = Store::all();
        return compact('products', 'stores');
    }

    public function getCreateData(): array
    {
        $stores = Store::all();
        return compact('stores');
    }

    public function createProduct(StoreProductRequest $request): void
    {
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
        }
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath ?? null,
            'store_id' => $request->store_id,
        ]);
    }

    public function getEditData($id): array
    {
        $product = Product::findOrFail($id);
        $stores = Store::all();
        return compact('product', 'stores');
    }

    public function updateProduct(UpdateProductRequest $request, $id): void
    {
        $product = Product::findOrFail($id);
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
        }
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => isset($imagePath) ? $imagePath : $product->image,
            'store_id' => $request->store_id,
        ]);
    }

    public function deleteProduct($id): void
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }
}