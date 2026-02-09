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
        Schema::create('video_downloads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User who downloaded
            $table->unsignedBigInteger('video_id'); // Video that was downloaded
            $table->string('ip_address')->nullable(); // IP address of download
            $table->string('user_agent')->nullable(); // User agent string
            $table->timestamp('downloaded_at')->useCurrent(); // When the download occurred
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');

            // Indexes for performance
            $table->index(['user_id', 'video_id']);
            $table->index(['downloaded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_downloads');
    }
};
