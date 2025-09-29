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
        Schema::create('video_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->integer('order')->default(1); // Order of questions within the video
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['video_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_questions');
    }
};