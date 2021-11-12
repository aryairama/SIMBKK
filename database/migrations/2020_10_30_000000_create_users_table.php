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
            $table->string('npsn', 8)->nullable();
            $table->foreign('npsn')->references('npsn')->on('sekolah')->onDelete('cascade')->onUpdate('cascade');
            $table->string('username', 10);
            $table->string('password');
            $table->enum('roles', ['admin','pengurus_sekolah']);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
