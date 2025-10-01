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
            // Add address relationship
            $table->foreignId('address_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            
            // Add detailed shipping information
            $table->string('shipping_contact_name')->nullable()->after('phone');
            $table->string('shipping_contact_phone')->nullable()->after('shipping_contact_name');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_district')->nullable()->after('shipping_city');
            $table->string('shipping_postal_code')->nullable()->after('shipping_district');
            $table->decimal('shipping_latitude', 10, 8)->nullable()->after('shipping_postal_code');
            $table->decimal('shipping_longitude', 11, 8)->nullable()->after('shipping_latitude');
            $table->enum('shipping_delivery_instruction', ['hand_to_me', 'leave_at_spot'])->nullable()->after('shipping_longitude');
            $table->string('shipping_drop_off_location')->nullable()->after('shipping_delivery_instruction');
            $table->text('shipping_additional_notes')->nullable()->after('shipping_drop_off_location');
            
            // Tracking information
            $table->string('tracking_number')->nullable()->after('shipping_additional_notes');
            $table->string('courier_company')->nullable()->after('tracking_number');
            $table->timestamp('estimated_delivery_date')->nullable()->after('courier_company');
            
            // Add index for tracking
            $table->index('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropIndex(['tracking_number']);
            
            $table->dropColumn([
                'address_id',
                'shipping_contact_name',
                'shipping_contact_phone',
                'shipping_city',
                'shipping_district',
                'shipping_postal_code',
                'shipping_latitude',
                'shipping_longitude',
                'shipping_delivery_instruction',
                'shipping_drop_off_location',
                'shipping_additional_notes',
                'tracking_number',
                'courier_company',
                'estimated_delivery_date'
            ]);
        });
    }
};

