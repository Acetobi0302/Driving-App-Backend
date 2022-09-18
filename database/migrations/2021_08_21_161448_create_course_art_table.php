<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseArtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_art', function (Blueprint $table) {
            $table->increments('id');
            $table->string('course_name');
            $table->smallInteger('fees');
            $table->smallInteger('course_time_duration');
            $table->string('art')->nullable();
            $table->unsignedInteger('classes_id')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('classes_id')->references('id')->on('classes');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_art');
    }
}
