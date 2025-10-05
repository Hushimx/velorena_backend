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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Basic post information
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->string('slug')->unique();
            $table->longText('content');
            $table->longText('content_ar')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('excerpt_ar')->nullable();
            $table->string('featured_image')->nullable();

            // Status and visibility
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();

            // Author
            $table->foreignId('admin_id')->constrained()->onDelete('cascade');

            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->string('meta_title_ar')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_description_ar')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_keywords_ar')->nullable();

            // Open Graph
            $table->string('og_title')->nullable();
            $table->string('og_title_ar')->nullable();
            $table->text('og_description')->nullable();
            $table->text('og_description_ar')->nullable();
            $table->string('og_image')->nullable();

            // Additional SEO
            $table->string('canonical_url')->nullable();
            $table->string('robots')->default('index,follow');
            $table->json('structured_data')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'published_at']);
            $table->index(['is_featured', 'status']);
            $table->index('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
