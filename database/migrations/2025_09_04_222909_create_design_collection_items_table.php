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
        Schema::create('design_collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('design_collections')->onDelete('cascade');
            $table->foreignId('design_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();

            // Ensure unique combination of collection and design
            $table->unique(['collection_id', 'design_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_collection_items');
    }
};
