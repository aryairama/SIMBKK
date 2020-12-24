<?php

namespace App\Http\Controllers;

use App\Siswa;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Illuminate\Support\Facades\Gate;
use File;

class ImportExcelController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Gate::allows('roleOperatorSekolah')) {
                return $next($request);
            }
            abort(403);
        });
    }
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

    public function index()
    {
        return view('import_siswa.index');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'export_siswa' => 'required',
        ]);
        $extension = File::extension($request->export_siswa->getClientOriginalName());
        if ($extension !== "xlsx") {
            return \Response::json(422);
        }
        $error = array();
        $angkatans = array();
        $keterserapans = array();
        $komlis = array();
        $nama = array();
        $reader = new Xlsx();
        $siswa_angkatan = \App\Angkatan::get('angkatan_id')->toArray();
        $siswa_keterserapan= \App\Keterserapan::get('keterserapan_id')->toArray();
        $siswa_komli = \App\Komli::get('komli_id')->toArray();
        foreach ($siswa_angkatan as $key => $angkatan) {
            $angkatans[$key] = $angkatan['angkatan_id'];
        }
        foreach ($siswa_keterserapan as $key => $keterserapan) {
            $keterserapans[$key] = $keterserapan['keterserapan_id'];
        }
        foreach ($siswa_komli as $key => $komli) {
            $komlis[$key] = $komli['komli_id'];
        }
        $preview = $reader->load($request->file('export_siswa'));
        try {
            $previewExcel = $preview->getActiveSheet()->toArray();
        } catch (\Throwable $th) {
            return \Response::json(500);
        }
        if ((strval($previewExcel['0']['0'] !== "NISN"))
        || (strval($previewExcel['0']['1'] !== "Nama"))
        || (strval($previewExcel['0']['2'] !== "Angkatan"))
        || (strval($previewExcel['0']['3'] !== "Tempat Lahir"))
        || (strval($previewExcel['0']['4'] !== "Tanggl Lahir"))
        || (strval($previewExcel['0']['5'] !== "Jenis kelamin"))
        || (strval($previewExcel['0']['6'] !== "Komli"))
        || (strval($previewExcel['0']['7'] !== "Prestasi"))
        || (strval($previewExcel['0']['8'] !== "Keterserapan"))
        || (strval($previewExcel['0']['9'] !== "Keterangan"))) {
            return \Response::json(415);
        } elseif (count($previewExcel) < 2) {
            return \Response::json(501);
        }
        for ($i = 1; $i < count($previewExcel); $i++) {
            $nama[$i]['nisn'] = $previewExcel[$i]['0'];
            $nama[$i]['siswa_nama'] = $previewExcel[$i]['1'];
            $nama[$i]['siswa_angkatan'] = $previewExcel[$i]['2'];
            $nama[$i]['tempat_lahir'] = $previewExcel[$i]['3'];
            $nama[$i]['tanggal_lahir'] = $previewExcel[$i]['4'];
            $nama[$i]['siswa_jk'] = $previewExcel[$i]['5'];
            $nama[$i]['siswa_komli'] = $previewExcel[$i]['6'];
            $nama[$i]['siswa_prestasi'] = $previewExcel[$i]['7'];
            $nama[$i]['siswa_keterserapan'] = $previewExcel[$i]['8'];
            $nama[$i]['keterangan'] = $previewExcel[$i]['9'];
            if ((!in_array($nama[$i]['siswa_angkatan'], $angkatans))
            ||(!in_array($nama[$i]['siswa_komli'], $komlis))
            ||(!in_array($nama[$i]['siswa_keterserapan'], $keterserapans))) {
                $error[$i]['angkatan'] = null;
                $error[$i]['komli'] = null;
                $error[$i]['keterserapan'] = null;
            }
            if (!in_array($nama[$i]['siswa_angkatan'], $angkatans)) {
                $error[$i]['angkatan'] = "NISN : " . $nama[$i]['nisn'] . ",Nama Siswa : ".$nama[$i]['siswa_nama'].",Nilai angkatan tidak sesuai dengan data Angkatan yang ada";
            }
            if (!in_array($nama[$i]['siswa_komli'], $komlis)) {
                $error[$i]['komli'] = "NISN : " . $nama[$i]['nisn'] . ",Nama Siswa : ".$nama[$i]['siswa_nama'].",Nilai Komli tidak sesuai dengan data Komli yang ada";
            }
            if (!in_array($nama[$i]['siswa_keterserapan'], $keterserapans)) {
                $error[$i]['keterserapan'] = "NISN : " . $nama[$i]['nisn'] . ",Nama Siswa : ".$nama[$i]['siswa_nama'].",Nilai Keterserapan tidak sesuai dengan data Keterserapan yang ada";
            }
        }
        if (\count($error)>0) {
            return \Response::json($error, 403);
        } else {
            return $nama;
        }
    }

    public function saveImportExcel(Request $request)
    {
        $data = array();
        foreach ($request->export_siswa as $key => $export) {
            $data[] = [
                "nisn" => $export['nisn'],
                "siswa_nama" => $export['siswa_nama'],
                "siswa_sekolah" => \Auth::user()->npsn,
                "siswa_angkatan" => $export['siswa_angkatan'],
                "tempat_lahir" => $export['tempat_lahir'],
                "tanggal_lahir" => $export['tanggal_lahir'],
                "siswa_jk" => $export['siswa_jk'],
                "siswa_komli" => $export['siswa_komli'],
                "siswa_prestasi" => $export['siswa_prestasi'],
                "siswa_keterserapan" => $export['siswa_keterserapan'],
                "keterangan" => $export['keterangan'],
            ];
        }
        Siswa::insert($data);
        return \Response::json(200);
    }
}
