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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('amount', 10, 2); // Total amount paid by user
            $table->decimal('commission_rate', 5, 2); // Admin commission percentage
            $table->decimal('commission_amount', 10, 2); // Actual commission amount
            $table->decimal('creator_earning', 10, 2); // Amount going to creator
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->timestamp('paid_at')->nullable();
            $table->json('stripe_data')->nullable(); // Store Stripe response data
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['creator_id', 'status']);
            $table->index(['order_number']);
            $table->index(['stripe_payment_intent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
