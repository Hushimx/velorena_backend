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
        Schema::table('design_favorites', function (Blueprint $table) {
            $table->string('custom_image_url')->nullable()->after('notes');
            $table->string('image_type')->nullable()->after('custom_image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('design_favorites', function (Blueprint $table) {
            $table->dropColumn(['custom_image_url', 'image_type']);
        });
    }
};
