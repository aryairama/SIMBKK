<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = "sekolah";
    protected $primaryKey = "npsn";
    public $incrementing = false;
    protected $fillable = [
        'npsn', 'sekolah_nama', 'sekolah_kepsek','sekolah_email','kec','kab','kode_pos'
    ];

    public function userss()
    {
        return $this->hasOne("App\User", "npsn", "npsn");
    }

    public function siswas()
    {
        return $this->hasMany("App\Siswa", "npsn", "siswa_sekolah");
    }
}
