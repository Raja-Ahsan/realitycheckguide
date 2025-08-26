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
        Schema::create('creator_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id'); // Creator these rules apply to
            $table->integer('videos_sold_threshold')->default(15); // Minimum videos sold to unlock custom pricing
            $table->decimal('max_price_cap', 8, 2)->default(99.99); // Maximum price creator can set
            $table->decimal('min_price_floor', 8, 2)->default(0.99); // Minimum price creator can set
            $table->boolean('custom_pricing_enabled')->default(false); // Can creator set custom prices?
            $table->json('pricing_tiers')->nullable(); // Bulk pricing tiers if applicable
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes for performance
            $table->index(['creator_id', 'custom_pricing_enabled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creator_pricing_rules');
    }
};
