<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_management', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manufacturer');
            $table->string('model')->nullable();
            $table->string('color');
            $table->string('number_plate');
            $table->string('art')->nullable();
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
        Schema::dropIfExists('car_management');
    }
}
