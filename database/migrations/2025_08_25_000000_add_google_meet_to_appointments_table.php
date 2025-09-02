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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('google_meet_id')->nullable()->after('designer_notes');
            $table->string('google_meet_link')->nullable()->after('google_meet_id');
            $table->timestamp('meet_created_at')->nullable()->after('google_meet_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['google_meet_id', 'google_meet_link', 'meet_created_at']);
        });
    }
};
