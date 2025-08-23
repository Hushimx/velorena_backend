<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];

        foreach ($users as $user) {
            // Create 2-5 orders per user
            $orderCount = rand(2, 5);

            for ($i = 0; $i < $orderCount; $i++) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => Order::generateOrderNumber(),
                    'status' => $statuses[array_rand($statuses)],
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0,
                    'notes' => rand(0, 1) ? 'Sample order notes for testing purposes.' : null,
                    'shipping_address' => rand(0, 1) ? '123 Main Street, City, Country' : null,
                    'billing_address' => rand(0, 1) ? '123 Main Street, City, Country' : null,
                    'phone' => '+966' . rand(500000000, 599999999),
                ]);

                // Add 1-3 products to each order
                $itemCount = rand(1, 3);
                $subtotal = 0;

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $unitPrice = $product->base_price;
                    $totalPrice = $unitPrice * $quantity;
                    $subtotal += $totalPrice;

                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'options' => [],
                        'notes' => null,
                    ]);
                }

                // Calculate totals
                $tax = $subtotal * 0.15; // 15% VAT
                $total = $subtotal + $tax;

                $order->update([
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total,
                ]);
            }
        }
    }
}
