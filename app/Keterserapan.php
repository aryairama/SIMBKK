<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keterserapan extends Model
{
    protected $table = "keterserapan";
    protected $primaryKey = "keterserapan_id";
    protected $fillable = ["keterserapan_nama"];

    public function siswas()
    {
        return $this->hasMany("App\Siswa", "keterserapan_id", "siswa_keterserapan");
    }
}
