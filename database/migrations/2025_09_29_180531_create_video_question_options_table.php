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
        Schema::create('video_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_question_id')->constrained()->onDelete('cascade');
            $table->text('option_text');
            $table->integer('option_order')->default(1); // Order of options (1-4)
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            
            $table->index(['video_question_id', 'option_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_question_options');
    }
};