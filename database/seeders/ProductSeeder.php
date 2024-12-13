<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Store;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = Store::all();

        $stores->each(function ($store) {
            Product::factory(4)->create([
                'store_id' => $store->id,
            ]);
        });
    }
}
