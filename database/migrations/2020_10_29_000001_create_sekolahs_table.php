<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSekolahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->string('npsn', 8);
            $table->primary('npsn');
            $table->string('sekolah_nama')->nullable();
            $table->string('sekolah_kepsek')->nullable();
            $table->string('sekolah_email')->nullable()->unique();
            $table->string('kec')->nullable();
            $table->string('kab')->nullable();
            $table->integer('kode_pos')->nullable();
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
        Schema::dropIfExists('sekolahs');
    }
}
