<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('uploaded_by');
            $table->string('original_filename');
            $table->string('stored_path');
            $table->json('sections')->nullable(); // parsed sections metadata
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specifications');
    }
};


