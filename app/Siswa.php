<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = "siswa";
    protected $primaryKey = "siswa_id";
    protected $fillable = [
        'nisn', 'siswa_nama', 'siswa_sekolah','siswa_angkatan','tempat_lahir','tanggal_lahir','siswa_jk','siswa_keterserapan','keterangan'
    ];

    public function sekolahs()
    {
        return $this->belongsTo("App\Sekolah", "siswa_sekolah", "npsn");
    }

    public function komlis()
    {
        return $this->belongsTo("App\Komli", "siswa_komli", "komli_id");
    }

    public function keterserapans()
    {
        return $this->belongsTo("App\Keterserapan", "siswa_keterserapan", "keterserapan_id");
    }

    public function angkatans()
    {
        return $this->belongsTo("App\Angkatan", "siswa_angkatan", "angkatan_id");
    }
}
