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
        Schema::create('video_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User who purchased
            $table->unsignedBigInteger('video_id'); // Video that was purchased
            $table->decimal('amount_paid', 8, 2); // Amount paid for the video
            $table->string('payment_method')->nullable(); // Payment method used
            $table->string('transaction_id')->nullable(); // External transaction ID
            $table->string('status')->default('completed'); // completed, failed, refunded
            $table->timestamp('purchased_at')->useCurrent(); // When the purchase was made
            $table->timestamp('expires_at')->nullable(); // If video access expires
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');

            // Unique constraint to prevent duplicate purchases
            $table->unique(['user_id', 'video_id']);

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['video_id', 'status']);
            $table->index(['purchased_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_purchases');
    }
};
