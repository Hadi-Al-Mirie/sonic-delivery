<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
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
        for ($i = 1; $i <= 10; $i++) {
            $order = Order::create([
                'user_id' => $users->random()->id,
                'status' => 'pending',
                'total_price' => 0,
            ]);
            $totalPrice = 0;
            for ($j = 1; $j <= 3; $j++) {
                $product = $products->random();
                $quantity = rand(1, 5);
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
                $totalPrice += $orderItem->price * $quantity;
            }
            $order->update(['total_price' => $totalPrice]);
        }
    }
}
