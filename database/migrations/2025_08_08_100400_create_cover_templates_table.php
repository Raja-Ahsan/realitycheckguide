<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cover_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->string('name');
            $table->string('stored_path'); // uploaded template file (PDF or blade-exported PDF)
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cover_templates');
    }
};


