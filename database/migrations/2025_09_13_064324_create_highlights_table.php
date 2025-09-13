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
        Schema::create('highlights', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // English name like "Spring Offers"
            $table->string('name_ar'); // Arabic name like "عروض الربيع"
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('color')->default('#3B82F6'); // Hex color for the highlight badge
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('highlights');
    }
};