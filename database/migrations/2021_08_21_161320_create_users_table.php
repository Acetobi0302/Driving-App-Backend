<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('role');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('franchise_id')->index();
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
        Schema::dropIfExists('users');
    }
}
