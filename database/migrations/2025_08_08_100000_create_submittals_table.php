<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submittals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('created_by');
            $table->string('title');
            $table->string('status')->default('pending'); // pending, sent, approved, revise_resubmit, rejected
            $table->unsignedBigInteger('cover_template_id')->nullable();
            $table->timestamp('remind_at')->nullable()->after('cover_template_id');
            $table->string('received_document_path')->nullable()->after('remind_at');
            $table->string('vendor_email')->nullable()->after('received_document_path');
            $table->timestamp('last_sent_to_vendor_at')->nullable()->after('vendor_email');
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('created_by');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submittals');
    }
};


