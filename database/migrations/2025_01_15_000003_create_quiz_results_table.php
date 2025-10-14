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
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('user_email')->nullable();
            $table->foreignId('category_id')->constrained('quiz_categories')->onDelete('cascade');
            $table->integer('total_questions');
            $table->integer('correct_answers');
            $table->decimal('score_percentage', 5, 2);
            $table->timestamp('completed_at');
            $table->timestamps();
            
            $table->index(['category_id', 'completed_at']);
            $table->index(['user_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};
