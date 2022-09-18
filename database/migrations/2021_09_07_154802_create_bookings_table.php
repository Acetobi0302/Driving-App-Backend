<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('driver_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('car_id');
            $table->date('date');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('note')->nullable();
            $table->tinyInteger('paid')->default('0');
            $table->unsignedInteger('franchise_id')->index();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('franchise_id')->references('id')->on('franchise');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
