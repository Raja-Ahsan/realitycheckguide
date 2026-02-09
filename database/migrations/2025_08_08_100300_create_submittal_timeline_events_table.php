<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submittal_timeline_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submittal_id');
            $table->string('event_type'); // sent, received, comment_extracted, vendor_sent, vendor_returned, approved, revise_resubmit
            $table->text('message')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('happened_at')->useCurrent();
            $table->timestamps();

            $table->index('submittal_id');
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submittal_timeline_events');
    }
};


