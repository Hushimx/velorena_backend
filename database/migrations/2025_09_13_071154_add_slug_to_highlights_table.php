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
        Schema::table('highlights', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name_ar');
        });
        
        // Update existing highlights with slugs
        $highlights = \App\Models\Highlight::all();
        foreach ($highlights as $highlight) {
            $highlight->slug = \App\Models\Highlight::generateSlug($highlight->name);
            $highlight->save();
        }
        
        // Now make it unique
        Schema::table('highlights', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('highlights', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};