<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->string('car_type_slug')->comment('tesla');
            $table->string('product_slug');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('pickup_date');
            $table->string('pickup_time');
            $table->string('return_date');
            $table->string('return_time');
            $table->text('description');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
