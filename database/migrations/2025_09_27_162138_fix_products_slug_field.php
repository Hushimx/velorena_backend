<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, check if slug column exists and update existing products with slugs
        if (Schema::hasColumn('products', 'slug')) {
            // Update existing products that don't have slugs
            $products = \App\Models\Product::whereNull('slug')->get();
            foreach ($products as $product) {
                $product->slug = \App\Models\Product::generateSlug($product->name);
                $product->save();
            }

            // Make slug column nullable temporarily
            Schema::table('products', function (Blueprint $table) {
                $table->string('slug')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is safe to rollback as it only makes changes to existing data
        // No schema changes to reverse
    }
};
