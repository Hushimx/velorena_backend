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
            // Check if columns exist before adding them
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('description_ar');
            }
            if (!Schema::hasColumn('products', 'meta_title_ar')) {
                $table->string('meta_title_ar')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title_ar');
            }
            if (!Schema::hasColumn('products', 'meta_description_ar')) {
                $table->text('meta_description_ar')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('products', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_description_ar');
            }
            if (!Schema::hasColumn('products', 'meta_keywords_ar')) {
                $table->string('meta_keywords_ar')->nullable()->after('meta_keywords');
            }

            // Open Graph Fields
            if (!Schema::hasColumn('products', 'og_title')) {
                $table->string('og_title')->nullable()->after('meta_keywords_ar');
            }
            if (!Schema::hasColumn('products', 'og_title_ar')) {
                $table->string('og_title_ar')->nullable()->after('og_title');
            }
            if (!Schema::hasColumn('products', 'og_description')) {
                $table->text('og_description')->nullable()->after('og_title_ar');
            }
            if (!Schema::hasColumn('products', 'og_description_ar')) {
                $table->text('og_description_ar')->nullable()->after('og_description');
            }
            if (!Schema::hasColumn('products', 'og_image')) {
                $table->string('og_image')->nullable()->after('og_description_ar');
            }

            // Twitter Card Fields
            if (!Schema::hasColumn('products', 'twitter_title')) {
                $table->string('twitter_title')->nullable()->after('og_image');
            }
            if (!Schema::hasColumn('products', 'twitter_title_ar')) {
                $table->string('twitter_title_ar')->nullable()->after('twitter_title');
            }
            if (!Schema::hasColumn('products', 'twitter_description')) {
                $table->text('twitter_description')->nullable()->after('twitter_title_ar');
            }
            if (!Schema::hasColumn('products', 'twitter_description_ar')) {
                $table->text('twitter_description_ar')->nullable()->after('twitter_description');
            }
            if (!Schema::hasColumn('products', 'twitter_image')) {
                $table->string('twitter_image')->nullable()->after('twitter_description_ar');
            }

            // Additional SEO Fields
            if (!Schema::hasColumn('products', 'canonical_url')) {
                $table->string('canonical_url')->nullable()->after('twitter_image');
            }
            if (!Schema::hasColumn('products', 'robots')) {
                $table->string('robots')->default('index,follow')->after('canonical_url');
            }
            if (!Schema::hasColumn('products', 'structured_data')) {
                $table->json('structured_data')->nullable()->after('robots');
            }
        });

        // Add indexes only if they don't exist
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasIndex('products', 'products_meta_title_index')) {
                $table->index('meta_title');
            }
            if (!Schema::hasIndex('products', 'products_canonical_url_index')) {
                $table->index('canonical_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['meta_title']);
            $table->dropIndex(['canonical_url']);

            // Drop SEO fields
            $table->dropColumn([
                'meta_title',
                'meta_title_ar',
                'meta_description',
                'meta_description_ar',
                'meta_keywords',
                'meta_keywords_ar',
                'og_title',
                'og_title_ar',
                'og_description',
                'og_description_ar',
                'og_image',
                'twitter_title',
                'twitter_title_ar',
                'twitter_description',
                'twitter_description_ar',
                'twitter_image',
                'canonical_url',
                'robots',
                'structured_data'
            ]);
        });
    }
};
