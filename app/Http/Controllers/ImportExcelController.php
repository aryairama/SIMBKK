<?php

namespace App\Http\Controllers;

use App\Siswa;
use Illuminate\Http\Request;

class ImportExcelController extends Controller
{
    public function test()
    {
        $json = \File::get('data_siswa/test.json');
        $data = \json_decode($json, true);
        foreach ($data as $key => $d) {
            $siswa = new Siswa();
            $siswa->nisn = $d['nisn'];
            $siswa->siswa_nama = $d['siswa_nama'];
            $siswa->siswa_sekolah = $d['siswa_sekolah'];
            $siswa->siswa_angkatan = $d['siswa_angkatan'];
            $siswa->tempat_lahir = $d['tempat_lahir'];
            $siswa->tanggal_lahir = $d['tanggal_lahir'];
            $siswa->siswa_jk = $d['siswa_jk'];
            $siswa->siswa_komli = $d['siswa_komli'];
            $siswa->siswa_prestasi = $d['siswa_prestasi'];
            $siswa->siswa_keterserapan = $d['siswa_keterserapan'];
            $siswa->keterangan = $d['keterangan'];
            $siswa->save();
        }
    }
}
