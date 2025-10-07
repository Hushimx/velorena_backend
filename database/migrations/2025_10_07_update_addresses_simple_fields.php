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
        Schema::table('addresses', function (Blueprint $table) {
            // Drop old fields that we don't need
            $table->dropColumn([
                'address_line',
                'latitude',
                'longitude',
                'delivery_instruction',
                'drop_off_location',
                'additional_notes',
                'building_image_url'
            ]);
            
            // Add new simple fields matching the form
            $table->string('street')->nullable()->after('district'); // الشارع
            $table->text('house_description')->nullable()->after('street'); // وصف البيت
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Add back old fields
            $table->text('address_line')->after('contact_phone');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('delivery_instruction', ['hand_to_me', 'leave_at_spot'])->nullable();
            $table->string('drop_off_location')->nullable();
            $table->text('additional_notes')->nullable();
            $table->string('building_image_url')->nullable();
            
            // Drop new fields
            $table->dropColumn([
                'street',
                'house_description'
            ]);
        });
    }
};

