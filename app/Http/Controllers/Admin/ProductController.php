<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, ProductService $productService)
    {
        $data = $productService->getIndexData($request);
        return view('dashboard.products.index', $data);
    }

    public function create(ProductService $productService)
    {
        $data = $productService->getCreateData();
        return view('dashboard.products.add', $data);
    }

    public function store(StoreProductRequest $request, ProductService $productService)
    {
        $productService->createProduct($request);
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id, ProductService $productService)
    {
        $data = $productService->getEditData($id);
        return view('dashboard.products.edit', $data);
    }

    public function update(UpdateProductRequest $request, $id, ProductService $productService)
    {
        $productService->updateProduct($request, $id);
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id, ProductService $productService)
    {
        $productService->deleteProduct($id);
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}