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
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'subtitle')) {
                $table->string('subtitle')->nullable()->after('title');
            }
            if (!Schema::hasColumn('categories', 'discover_points')) {
                $table->text('discover_points')->nullable();
            }
            // Add description column if it doesn't exist
            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable();
            }
            // Add image column if it doesn't exist
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'discover_points']);
        });
    }
};
