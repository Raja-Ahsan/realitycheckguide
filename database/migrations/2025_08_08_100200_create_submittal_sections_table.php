<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submittal_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submittal_id');
            $table->string('spec_section')->nullable(); // e.g., 26 05 33
            $table->string('title')->nullable(); // e.g., Raceway and Boxes for Electrical
            $table->string('manufacturer')->nullable();
            $table->string('product_type')->nullable();
            $table->json('extracted_data')->nullable();
            $table->boolean('included')->default(true); // include in submittal or not
            $table->timestamps();
            $table->softDeletes();

            $table->index('submittal_id');
            $table->index('spec_section');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submittal_sections');
    }
};


