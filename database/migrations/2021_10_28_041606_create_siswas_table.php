<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->increments('siswa_id');
            $table->string('nisn', 10);
            $table->string('siswa_nama');
            $table->string('siswa_sekolah', );
            $table->foreign('siswa_sekolah')->references('npsn')->on('sekolah')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('siswa_angkatan')->unsigned();
            $table->foreign('siswa_angkatan')->references('angkatan_id')->on('angkatan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->enum('siswa_jk', ['L','P']);
            $table->integer('siswa_komli')->unsigned();
            $table->foreign('siswa_komli')->references('komli_id')->on('komli')->onDelete('cascade')->onUpdate('cascade');
            $table->string('siswa_prestasi');
            $table->integer('siswa_keterserapan')->unsigned();
            $table->foreign('siswa_keterserapan')->references('keterserapan_id')->on('keterserapan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('keterangan');
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
        Schema::dropIfExists('siswa');
    }
}
