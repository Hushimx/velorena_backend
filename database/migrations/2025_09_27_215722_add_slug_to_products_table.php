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
        Schema::table('products', function (Blueprint $table) {
            // Add slug column if it doesn't exist
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable()->after('name_ar');
            }
        });

        // Generate slugs for existing products
        $products = \App\Models\Product::whereNull('slug')->get();
        foreach ($products as $product) {
            $product->slug = \App\Models\Product::generateSlug($product->name);
            $product->save();
        }

        // Make slug column unique and not nullable
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
