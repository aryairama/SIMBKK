<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    protected $table = "angkatan";
    protected $primaryKey = "angkatan_id";
    protected $fillable = ["angkatan_ket"];

    public function siswas()
    {
        return $this->hasMany("App\Siswa", "angkatan_id", "siswa_angkatan");
    }
}
