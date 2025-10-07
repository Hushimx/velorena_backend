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
        Schema::table('orders', function (Blueprint $table) {
            // Drop old shipping fields we don't need
            $table->dropColumn([
                'shipping_latitude',
                'shipping_longitude',
                'shipping_delivery_instruction',
                'shipping_drop_off_location',
                'shipping_additional_notes'
            ]);
            
            // Add simple shipping_street field
            $table->string('shipping_street')->nullable()->after('shipping_district'); // الشارع
            $table->text('shipping_house_description')->nullable()->after('shipping_street'); // وصف البيت
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add back old fields
            $table->decimal('shipping_latitude', 10, 8)->nullable();
            $table->decimal('shipping_longitude', 11, 8)->nullable();
            $table->enum('shipping_delivery_instruction', ['hand_to_me', 'leave_at_spot'])->nullable();
            $table->string('shipping_drop_off_location')->nullable();
            $table->text('shipping_additional_notes')->nullable();
            
            // Drop new fields
            $table->dropColumn([
                'shipping_street',
                'shipping_house_description'
            ]);
        });
    }
};

