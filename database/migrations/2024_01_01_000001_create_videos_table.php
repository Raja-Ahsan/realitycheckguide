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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_path'); // Path to stored video file
            $table->string('thumbnail_path')->nullable(); // Path to video thumbnail
            $table->string('duration')->nullable(); // Video duration in seconds
            $table->boolean('is_intro')->default(false); // Is this the free intro video?
            $table->decimal('price', 8, 2)->default(0.00); // Price in USD
            $table->boolean('downloads_enabled')->default(true); // Can users download this video?
            $table->string('status')->default('active'); // active, inactive, processing
            $table->unsignedBigInteger('creator_id'); // User who created the video
            $table->unsignedBigInteger('category_id')->nullable(); // Video category
            $table->json('tags')->nullable(); // Video tags for search
            $table->integer('views_count')->default(0); // Total views
            $table->integer('purchases_count')->default(0); // Total purchases
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

            // Indexes for performance
            $table->index(['creator_id', 'is_intro']);
            $table->index(['status', 'is_intro']);
            $table->index(['price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
