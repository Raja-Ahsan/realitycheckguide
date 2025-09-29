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
        Schema::create('video_question_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_question_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_question_option_id')->constrained()->onDelete('cascade');
            $table->boolean('is_correct')->default(false);
            $table->timestamp('answered_at');
            $table->timestamps();
            
            // Ensure one response per user per question
            $table->unique(['user_id', 'video_question_id'], 'unique_user_question_response');
            
            $table->index(['user_id', 'video_id']);
            $table->index(['video_question_id', 'is_correct']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_question_responses');
    }
};