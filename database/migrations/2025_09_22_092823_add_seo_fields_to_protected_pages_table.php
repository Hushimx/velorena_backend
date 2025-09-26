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
        Schema::table('protected_pages', function (Blueprint $table) {
            $table->string('meta_title')->nullable();
            $table->string('meta_title_ar')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_description_ar')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_keywords_ar')->nullable();
            $table->string('og_image')->nullable();
            $table->json('images')->nullable(); // For storing page images
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('protected_pages', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_title_ar',
                'meta_description',
                'meta_description_ar',
                'meta_keywords',
                'meta_keywords_ar',
                'og_image',
                'images'
            ]);
        });
    }
};
