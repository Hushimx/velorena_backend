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
        Schema::table('store_contents', function (Blueprint $table) {
            $table->string('key')->unique();
            $table->string('type')->default('setting'); // setting, content, media
            $table->json('value_en')->nullable();
            $table->json('value_ar')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_contents', function (Blueprint $table) {
            $table->dropColumn([
                'key',
                'type',
                'value_en',
                'value_ar',
                'description',
                'is_active',
                'sort_order'
            ]);
        });
    }
};
