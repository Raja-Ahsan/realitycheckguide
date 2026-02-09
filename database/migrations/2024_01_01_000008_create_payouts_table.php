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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->string('payout_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, approved, processing, completed, rejected
            $table->string('payout_method')->nullable(); // stripe, bank_transfer, manual
            $table->string('stripe_transfer_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('stripe_data')->nullable(); // Store Stripe transfer data
            $table->timestamps();
            
            $table->index(['creator_id', 'status']);
            $table->index(['payout_number']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
