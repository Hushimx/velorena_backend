<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class FixOrderTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:fix-totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix order totals for orders that have 0 total';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix order totals...');

        // Find orders with 0 total
        $ordersWithZeroTotal = Order::where('total', 0)->get();

        if ($ordersWithZeroTotal->isEmpty()) {
            $this->info('No orders with 0 total found.');
            return;
        }

        $this->info("Found {$ordersWithZeroTotal->count()} orders with 0 total.");

        $fixed = 0;
        $errors = 0;

        foreach ($ordersWithZeroTotal as $order) {
            try {
                // Calculate totals based on order items
                $subtotal = $order->items->sum('total_price');

                if ($subtotal > 0) {
                    $tax = $subtotal * 0.15; // 15% VAT
                    $total = $subtotal + $tax;

                    $order->update([
                        'subtotal' => $subtotal,
                        'tax' => $tax,
                        'total' => $total
                    ]);

                    $this->line("Fixed order {$order->order_number}: {$subtotal} + {$tax} = {$total}");
                    $fixed++;
                } else {
                    $this->warn("Order {$order->order_number} has no items or items with 0 price");
                }
            } catch (\Exception $e) {
                $this->error("Error fixing order {$order->order_number}: {$e->getMessage()}");
                $errors++;
            }
        }

        $this->info("Fixed {$fixed} orders. Errors: {$errors}");
    }
}
