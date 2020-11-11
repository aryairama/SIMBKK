<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komli extends Model
{
    protected $table = "komli";
    protected $primaryKey = "komli_id";
    protected $fillable = ["komli_nama"];

    public function siswas()
    {
        return $this->hasMany("App\Siswa", "komli_id", "siswa_komli");
    }
}
