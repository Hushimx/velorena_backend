<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class FixOrderItemPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:fix-item-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix order item prices that are 0';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix order item prices...');

        // Find order items with 0 total price
        $orderItemsWithZeroPrice = OrderItem::where('total_price', 0)->with(['product', 'order'])->get();

        if ($orderItemsWithZeroPrice->isEmpty()) {
            $this->info('No order items with 0 price found.');
            return;
        }

        $this->info("Found {$orderItemsWithZeroPrice->count()} order items with 0 price.");

        $fixed = 0;
        $errors = 0;

        foreach ($orderItemsWithZeroPrice as $orderItem) {
            try {
                $product = $orderItem->product;

                if (!$product) {
                    $this->warn("Order item {$orderItem->id} has no product");
                    continue;
                }

                // Calculate unit price based on product base price
                $unitPrice = $product->base_price;

                // Add option prices if options exist
                if ($orderItem->options && is_array($orderItem->options)) {
                    foreach ($orderItem->options as $optionId => $valueId) {
                        $optionValue = \App\Models\OptionValue::find($valueId);
                        if ($optionValue && $optionValue->price_adjustment) {
                            $unitPrice += $optionValue->price_adjustment;
                        }
                    }
                }

                $totalPrice = $unitPrice * $orderItem->quantity;

                $orderItem->update([
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice
                ]);

                $this->line("Fixed order item {$orderItem->id}: unit_price={$unitPrice}, total_price={$totalPrice}");
                $fixed++;

                // Recalculate order totals
                $order = $orderItem->order;
                if ($order) {
                    $order->calculateTotals();
                    $this->line("  Recalculated order {$order->order_number}: total={$order->total}");
                }
            } catch (\Exception $e) {
                $this->error("Error fixing order item {$orderItem->id}: {$e->getMessage()}");
                $errors++;
            }
        }

        $this->info("Fixed {$fixed} order items. Errors: {$errors}");
    }
}
