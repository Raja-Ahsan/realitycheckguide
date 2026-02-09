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
        Schema::table('videos', function (Blueprint $table) {
            // Add new fields for enhanced video management
            $table->integer('videos_sold')->default(0)->after('purchases_count');
            $table->boolean('is_featured')->default(false)->after('is_intro');
            $table->string('video_quality')->default('HD')->after('duration');
            $table->text('learning_objectives')->nullable()->after('description');
            $table->text('prerequisites')->nullable()->after('learning_objectives');
            $table->string('difficulty_level')->default('beginner')->after('prerequisites');
            $table->json('tags_array')->nullable()->after('tags'); // Store tags as JSON for better search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn([
                'videos_sold',
                'is_featured',
                'video_quality',
                'learning_objectives',
                'prerequisites',
                'difficulty_level',
                'tags_array'
            ]);
        });
    }
};
