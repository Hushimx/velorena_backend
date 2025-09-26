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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable()->after('name_ar');
            }
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('products', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('products', 'og_title')) {
                $table->string('og_title')->nullable()->after('meta_keywords');
            }
            if (!Schema::hasColumn('products', 'og_description')) {
                $table->text('og_description')->nullable()->after('og_title');
            }
            if (!Schema::hasColumn('products', 'og_image')) {
                $table->string('og_image')->nullable()->after('og_description');
            }
            if (!Schema::hasColumn('products', 'structured_data')) {
                $table->json('structured_data')->nullable()->after('og_image');
            }
        });

        // Generate slugs for existing products if slug column exists
        if (Schema::hasColumn('products', 'slug')) {
            $products = \App\Models\Product::whereNull('slug')->orWhere('slug', '')->get();
            foreach ($products as $product) {
                $product->slug = \Illuminate\Support\Str::slug($product->name);
                $counter = 1;
                $originalSlug = $product->slug;

                while (\App\Models\Product::where('slug', $product->slug)->where('id', '!=', $product->id)->exists()) {
                    $product->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $product->save();
            }

            // Make slug unique if it's not already
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->unique('slug');
                });
            } catch (\Exception $e) {
                // Unique constraint might already exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'structured_data')) {
                $table->dropColumn('structured_data');
            }
            if (Schema::hasColumn('products', 'og_image')) {
                $table->dropColumn('og_image');
            }
            if (Schema::hasColumn('products', 'og_description')) {
                $table->dropColumn('og_description');
            }
            if (Schema::hasColumn('products', 'og_title')) {
                $table->dropColumn('og_title');
            }
            if (Schema::hasColumn('products', 'meta_keywords')) {
                $table->dropColumn('meta_keywords');
            }
            if (Schema::hasColumn('products', 'meta_description')) {
                $table->dropColumn('meta_description');
            }
            if (Schema::hasColumn('products', 'meta_title')) {
                $table->dropColumn('meta_title');
            }
            if (Schema::hasColumn('products', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
