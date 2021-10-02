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
        try {
            $preview = $reader->load($request->file('export_siswa'));
            $previewExcel = $preview->getActiveSheet();
        } catch (\Throwable $th) {
            return \Response::json([
                'status' => 'Error',
                'message' => 'Max Load Memory Server',
                'data' => null
            ], 500);
        }
        if (strval($previewExcel->getCell('A1')->getValue()) !== "NISN"
        || strval($previewExcel->getCell('B1')->getValue()) !== "Nama"
        || strval($previewExcel->getCell('C1')->getValue()) !== "Angkatan"
        || strval($previewExcel->getCell('D1')->getValue()) !== "Tempat Lahir"
        || strval($previewExcel->getCell('E1')->getValue()) !== "Tanggl Lahir"
        || strval($previewExcel->getCell('F1')->getValue()) !== "Jenis kelamin"
        || strval($previewExcel->getCell('G1')->getValue()) !== "Komli"
        || strval($previewExcel->getCell('H1')->getValue()) !== "Prestasi"
        || strval($previewExcel->getCell('I1')->getValue()) !== "Keterserapan"
        || strval($previewExcel->getCell('J1')->getValue()) !== "Keterangan") {
            return \Response::json(415);
        } elseif ($previewExcel->getHighestRow() < 2) {
            return \Response::json(501);
        }
        for ($i = 1; $i < $previewExcel->getHighestRow(); $i++) {
            $nama[$i]['nisn'] = $previewExcel->getCell('A'.($i+1))->getValue();
            $nama[$i]['siswa_nama'] = $previewExcel->getCell('B'.($i+1))->getValue();
            $nama[$i]['siswa_angkatan'] = $previewExcel->getCell('C'.($i+1))->getValue();
            $nama[$i]['tempat_lahir'] = $previewExcel->getCell('D'.($i+1))->getValue();
            $nama[$i]['tanggal_lahir'] = $previewExcel->getCell('E'.($i+1))->getValue();
            $nama[$i]['siswa_jk'] = $previewExcel->getCell('F'.($i+1))->getValue();
            $nama[$i]['siswa_komli'] = $previewExcel->getCell('G'.($i+1))->getValue();
            $nama[$i]['siswa_prestasi'] = $previewExcel->getCell('H'.($i+1))->getValue();
            $nama[$i]['siswa_keterserapan'] = $previewExcel->getCell('I'.($i+1))->getValue();
            $nama[$i]['keterangan'] = $previewExcel->getCell('J'.($i+1))->getValue();
            if (!in_array($nama[$i]['siswa_angkatan'], $angkatans, true)
            || !in_array($nama[$i]['siswa_komli'], $komlis, true)
            || !in_array($nama[$i]['siswa_keterserapan'], $keterserapans, true)) {
                $error[$i]['angkatan'] = null;
                $error[$i]['komli'] = null;
                $error[$i]['keterserapan'] = null;
            }
            if (!in_array($nama[$i]['siswa_angkatan'], $angkatans, true)) {
                $error[$i]['angkatan'] = "NISN : " . $nama[$i]['nisn'] . ",Nama Siswa : ".$nama[$i]['siswa_nama'].",Nilai angkatan tidak sesuai dengan data Angkatan yang ada";
            }
            if (!in_array($nama[$i]['siswa_komli'], $komlis, true)) {
                $error[$i]['komli'] = "NISN : " . $nama[$i]['nisn'] . ",Nama Siswa : ".$nama[$i]['siswa_nama'].",Nilai Komli tidak sesuai dengan data Komli yang ada";
            }
            if (!in_array($nama[$i]['siswa_keterserapan'], $keterserapans, true)) {
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
        $export_siswa = \json_decode($request->export_data, true);
        $data = array();
        foreach ($export_siswa as $key => $export) {
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
        foreach (array_chunk($data, 1000) as $dataTrim) {
            Siswa::insert($dataTrim);
        }
        return \Response::json(200);
    }
}
