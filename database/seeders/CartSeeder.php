<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $products = Product::all();

        for ($i = 1; $i <= 20; $i++) {
            $cartItem = Cart::create([
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
                'quantity' => rand(1, 5),
            ]);
        }
    }
}
