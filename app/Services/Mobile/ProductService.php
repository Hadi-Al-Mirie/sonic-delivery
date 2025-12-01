<?php

namespace App\Services\Mobile;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function getProductById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function getAllProducts(): Collection
    {
        return Product::all();
    }
}