<?php

namespace App\Http\Controllers;

use App\Siswa;
use App\Sekolah;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ExportExcelController extends Controller
{
    public function index()
    {
        return view('rekap.index');
    }
    public function rekapSekolah(Request $request)
    {
        $this->authorize('roleAdminOpeartor');
        if (Gate::allows('roleAdmin')) {
            $request->validate([
            'npsn' => 'required',
        ]);
            $sekolah_nama = Sekolah::findOrFail($request->npsn);
            $siswaSekolah = Siswa::with('sekolahs')->select('siswa_sekolah', 'siswa_komli')
        ->where('siswa_sekolah', $request->npsn)->has("komlis")
        ->distinct('siswa_komli')->get();
        } elseif (Gate::allows('roleOperatorSekolah')) {
            $sekolah_nama = Sekolah::findOrFail(\Auth::user()->npsn);
            $siswaSekolah = Siswa::with('sekolahs')->select('siswa_sekolah', 'siswa_komli')
        ->where('siswa_sekolah', \Auth::user()->npsn)->has("komlis")
        ->distinct('siswa_komli')->get();
        }
        if ($siswaSekolah->count() <= 0) {
            return \redirect()->route('rekap.index')->with('status_persekolah', 'Rekap Sekolah '.$sekolah_nama->sekolah_nama.' Tidak Tersedia');
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode('Rekap_Keterserapan_'.str_replace(' ', '_', $siswaSekolah[0]['sekolahs']['sekolah_nama']).date("_Y_m_d").'.xlsx').'"');
        $row = 5;
        $i = 0;
        $totalKeterserpan = 0;
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setCellValue('A2', 'Data Keterserapan Lulusan '.$siswaSekolah[0]['sekolahs']['sekolah_nama']);
        $sheet->mergeCells("C4:G4");//merger cell jumlah keterserapn
        $sheet->setCellValue('C4', "Jumlah");
        $sheet->mergeCells("A4:A5");//merger cell No
        $sheet->setCellValue('A4', "NO");
        $sheet->mergeCells("B4:B5");//merger cell nama sekolah
        $sheet->setCellValue('B4', "Jurusan");
        $sheet->setCellValue('C5', "Bekerja");
        $sheet->setCellValue('D5', "Melanjutkan");
        $sheet->setCellValue('E5', "Wiraswasta");
        $sheet->setCellValue('F5', "Belum Terserap");
        $sheet->setCellValue('G5', "Total");
        foreach ($siswaSekolah as $perSekolah) {
            $i++;
            $row++;
            $total1 += $this->rekapPerSekolah($perSekolah->siswa_sekolah, $perSekolah->siswa_komli, 1);
            $total2 += $this->rekapPerSekolah($perSekolah->siswa_sekolah, $perSekolah->siswa_komli, 2);
            $total3 += $this->rekapPerSekolah($perSekolah->siswa_sekolah, $perSekolah->siswa_komli, 3);
            $total4 += $this->rekapPerSekolah($perSekolah->siswa_sekolah, $perSekolah->siswa_komli, 4);
            $totalKeterserpan += $this->rekapPerSekolahTotal($perSekolah->siswa_sekolah, $perSekolah->siswa_komli);
            $sheet->setCellValue('A'.$row, $i);
            $sheet->setCellValue('B'.$row, $perSekolah->komlis->komli_nama);
            $sheet->setCellValue('C'.$row, $this->rekapPerSekolah($perSekolah->siswa_sekolah, $perSekolah->siswa_komli, 1));
            $sheet->setCellValue('D'.$row, $this->rekapPerSekolah($perSekolah->siswa_sekolah, $perSekolah->siswa_komli, 2));
            $sheet->setCellValue('E'.$row, $this->rekapPerSekolah($perSekolah->siswa_sekolah, $perSekolah->siswa_komli, 3));
            $sheet->setCellValue('F'.$row, $this->rekapPerSekolah($perSekolah->siswa_sekolah, $perSekolah->siswa_komli, 4));
            $sheet->setCellValue('G'.$row, $this->rekapPerSekolahTotal($perSekolah->siswa_sekolah, $perSekolah->siswa_komli));
        }
        //set auto width
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        //set auto width
        //grandtotal
        $sheet->setCellValue('B'.$row+=1, "Total");
        $sheet->setCellValue('C'.$row, $total1." (".$total1*100/$totalKeterserpan."%)");
        $sheet->setCellValue('D'.$row, $total2." (".$total2*100/$totalKeterserpan."%)");
        $sheet->setCellValue('E'.$row, $total3." (".$total3*100/$totalKeterserpan."%)");
        $sheet->setCellValue('F'.$row, $total4." (".$total4*100/$totalKeterserpan."%)");
        $sheet->setCellValue('G'.$row, $totalKeterserpan);
        //grandtotal
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A4:G5')->getAlignment()->setHorizontal('center');//aligment text,rata tengah
        $sheet->getStyle('A4:G5')->getAlignment()->setVertical('center');//aligment text,rata tengah
        // $sheet->getStyle('A4:G5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);//font color
        $sheet->getStyle('A4:G5')->getFont()->setBold(true);//bold font
        $sheet->getStyle('A4:G5')->getFont()->setSize(13);//font size
        $sheet->getStyle('A4:G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');//background
        $sheet->getStyle('A4:G'.$row)->applyFromArray($styleArray);//border
        $writer = new Xlsx($excel);
        $writer->save('php://output');
    }

    public static function rekapPerSekolah($npsn, $komli, $id)
    {
        if (Gate::allows('roleAdminOpeartor')) {
            return Siswa::where('siswa_sekolah', $npsn)->whereHas('komlis', function ($query) use ($komli) {
                $query->where('komli_id', $komli);
            })->whereHas('keterserapans', function ($query) use ($id) {
                $query->where("keterserapan_id", $id);
            })->count();
        } else {
            abort(403);
        }
    }

    public static function rekapPerSekolahTotal($npsn, $komli)
    {
        if (Gate::allows('roleAdminOpeartor')) {
            return Siswa::where('siswa_sekolah', $npsn)->whereHas('komlis', function ($query) use ($komli) {
                $query->where('komli_id', $komli);
            })->has('keterserapans')->count();
        } else {
            abort(403);
        }
    }

    public function rekapKomli(Request $request)
    {
        $this->authorize('roleAdmin');
        $request->validate([
            'komli' => 'required',
        ]);
        $komli = \App\Komli::where("komli_id", $request->komli)->select('komli_nama')->get();
        $siswaSekolah = Siswa::select('siswa_sekolah', 'siswa_komli')->where('siswa_komli', $request->komli)->distinct('siswa_sekolah')->get();
        if ($siswaSekolah->count() <= 0) {
            return \redirect()->route('rekap.index')->with('status_komli', 'Rekap Komli '.$komli[0]['komli_nama'].' tidak tersedia');
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode('rekap_komli_'.str_replace(' ', '_', $komli[0]['komli_nama']).date("_Y_m_d_h_i_sa").'.xlsx').'"');
        $row = 5;
        $i = 0;
        $totalKeterserpan = 0;
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setCellValue('A2', 'Data Keterserapan Lulusan Komli '.$komli[0]['komli_nama']);
        $sheet->mergeCells("C4:G4");//merger cell jumlah keterserapn
        $sheet->setCellValue('C4', "Jumlah");
        $sheet->mergeCells("A4:A5");//merger cell No
        $sheet->setCellValue('A4', "NO");
        $sheet->mergeCells("B4:B5");//merger cell nama sekolah
        $sheet->setCellValue('B4', "Komli");
        $sheet->setCellValue('C5', "Bekerja");
        $sheet->setCellValue('D5', "Melanjutkan");
        $sheet->setCellValue('E5', "Wiraswasta");
        $sheet->setCellValue('F5', "Belum Terserap");
        $sheet->setCellValue('G5', "Total");
        foreach ($siswaSekolah as $value) {
            $i++;
            $row++;
            $total1 += $this->rekapPerKomli($value->siswa_sekolah, $request->komli, 1);
            $total2 += $this->rekapPerKomli($value->siswa_sekolah, $request->komli, 2);
            $total3 += $this->rekapPerKomli($value->siswa_sekolah, $request->komli, 3);
            $total4 += $this->rekapPerKomli($value->siswa_sekolah, $request->komli, 4);
            $totalKeterserpan += $this->rekapPerKomliTotal($value->siswa_sekolah, $request->komli);
            $sheet->setCellValue('A'.$row, $i);
            $sheet->setCellValue('B'.$row, $value->sekolahs->sekolah_nama);
            $sheet->setCellValue('C'.$row, $this->rekapPerKomli($value->siswa_sekolah, $request->komli, 1));
            $sheet->setCellValue('D'.$row, $this->rekapPerKomli($value->siswa_sekolah, $request->komli, 2));
            $sheet->setCellValue('E'.$row, $this->rekapPerKomli($value->siswa_sekolah, $request->komli, 3));
            $sheet->setCellValue('F'.$row, $this->rekapPerKomli($value->siswa_sekolah, $request->komli, 4));
            $sheet->setCellValue('G'.$row, $this->rekapPerKomliTotal($value->siswa_sekolah, $request->komli));
        }
        //set auto width
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        //grendtotal
        $sheet->setCellValue('B'.$row+=1, "Total");
        $sheet->setCellValue('C'.$row, $total1." (".$total1*100/$totalKeterserpan."%)");
        $sheet->setCellValue('D'.$row, $total2." (".$total2*100/$totalKeterserpan."%)");
        $sheet->setCellValue('E'.$row, $total3." (".$total3*100/$totalKeterserpan."%)");
        $sheet->setCellValue('F'.$row, $total4." (".$total4*100/$totalKeterserpan."%)");
        $sheet->setCellValue('G'.$row, $totalKeterserpan);
        //grendtotal
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A4:G5')->getAlignment()->setHorizontal('center');//aligment text,rata tengah
        $sheet->getStyle('A4:G5')->getAlignment()->setVertical('center');//aligment text,rata tengah
        // $sheet->getStyle('A4:G5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);//font color
        $sheet->getStyle('A4:G5')->getFont()->setBold(true);//bold font
        $sheet->getStyle('A4:G5')->getFont()->setSize(13);//font size
        $sheet->getStyle('A4:G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');//background
        $sheet->getStyle('A4:G'.$row)->applyFromArray($styleArray);//border
        $writer = new Xlsx($excel);
        $writer->save('php://output');
    }
    public static function rekapPerKomli($npsn, $komli, $keterserapan)
    {
        if (Gate::allows('roleAdmin')) {
            return Siswa::where('siswa_sekolah', $npsn)->where('siswa_komli', $komli)->whereHas('keterserapans', function ($query) use ($keterserapan) {
                $query->where('keterserapan_id', $keterserapan);
            })->count();
        } else {
            abort(403);
        }
    }

    public static function rekapPerKomliTotal($npsn, $komli)
    {
        if (Gate::allows('roleAdmin')) {
            return Siswa::where('siswa_sekolah', $npsn)->where('siswa_komli', $komli)->has('keterserapans')->count();
        } else {
            abort(403);
        }
    }

    public function rekapAngkatan(Request $request)
    {
        $this->authorize('roleAdminOpeartor');
        $request->validate([
            'angkatan' => 'required',
        ]);
        $angkatan = \App\Angkatan::where("angkatan_id", $request->angkatan)->select('angkatan_ket')->get();
        if (Gate::allows('roleAdmin')) {
            $siswaSekolah = Siswa::select('siswa_sekolah')->where('siswa_angkatan', $request->angkatan)->distinct('siswa_sekolah')->get();
        } elseif (Gate::allows('roleOperatorSekolah')) {
            $siswaSekolah = Siswa::select('siswa_sekolah')->where('siswa_sekolah', \Auth::user()->npsn)->where('siswa_angkatan', $request->angkatan)->distinct('siswa_sekolah')->get();
        }
        if ($siswaSekolah->count() <= 0) {
            return \redirect()->route('rekap.index')->with('status_angkatan', 'Rekap Angkatan '.$angkatan[0]->angkatan_ket.' tidak tersedia');
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode('rekap_perangkatan_'.date("Y_m_d_h_i_sa").'.xlsx').'"');
        $row = 5;
        $i = 0;
        $totalKeterserpan = 0;
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setCellValue('A2', 'Data Keterserapan Lulusan SMK Kab. Trenggalek Tahun Angkatan '.$angkatan[0]['angkatan_ket']);
        $sheet->mergeCells("C4:G4");//merger cell jumlah keterserapn
        $sheet->setCellValue('C4', "Jumlah");
        $sheet->mergeCells("A4:A5");//merger cell No
        $sheet->setCellValue('A4', "NO");
        $sheet->mergeCells("B4:B5");//merger cell nama sekolah
        $sheet->setCellValue('B4', "Sekolah");
        $sheet->setCellValue('C5', "Bekerja");
        $sheet->setCellValue('D5', "Melanjutkan");
        $sheet->setCellValue('E5', "Wiraswasta");
        $sheet->setCellValue('F5', "Belum Terserap");
        $sheet->setCellValue('G5', "Total");
        foreach ($siswaSekolah as $value) {
            $i++;
            $row++;
            $total1 += $this->rekapPerangkatan($value->siswa_sekolah, $request->angkatan, 1);
            $total2 += $this->rekapPerangkatan($value->siswa_sekolah, $request->angkatan, 2);
            $total3 += $this->rekapPerangkatan($value->siswa_sekolah, $request->angkatan, 3);
            $total4 += $this->rekapPerangkatan($value->siswa_sekolah, $request->angkatan, 4);
            $totalKeterserpan += $this->rekapPerangkatanTotal($value->siswa_sekolah, $request->angkatan);
            $sheet->setCellValue('A'.$row, $i);
            $sheet->setCellValue('B'.$row, $value->sekolahs->sekolah_nama);
            $sheet->setCellValue('C'.$row, $this->rekapPerangkatan($value->siswa_sekolah, $request->angkatan, 1));
            $sheet->setCellValue('D'.$row, $this->rekapPerangkatan($value->siswa_sekolah, $request->angkatan, 2));
            $sheet->setCellValue('E'.$row, $this->rekapPerangkatan($value->siswa_sekolah, $request->angkatan, 3));
            $sheet->setCellValue('F'.$row, $this->rekapPerangkatan($value->siswa_sekolah, $request->angkatan, 4));
            $sheet->setCellValue('G'.$row, $this->rekapPerangkatanTotal($value->siswa_sekolah, $request->angkatan));
        }
        //set auto width
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        //grendtotal
        $sheet->setCellValue('B'.$row+=1, "Total");
        $sheet->setCellValue('C'.$row, $total1." (".$total1*100/$totalKeterserpan."%)");
        $sheet->setCellValue('D'.$row, $total2." (".$total2*100/$totalKeterserpan."%)");
        $sheet->setCellValue('E'.$row, $total3." (".$total3*100/$totalKeterserpan."%)");
        $sheet->setCellValue('F'.$row, $total4." (".$total4*100/$totalKeterserpan."%)");
        $sheet->setCellValue('G'.$row, $totalKeterserpan);
        //grendtotal
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A4:G5')->getAlignment()->setHorizontal('center');//aligment text,rata tengah
        $sheet->getStyle('A4:G5')->getAlignment()->setVertical('center');//aligment text,rata tengah
        // $sheet->getStyle('A4:G5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);//font color
        $sheet->getStyle('A4:G5')->getFont()->setBold(true);//bold font
        $sheet->getStyle('A4:G5')->getFont()->setSize(13);//font size
        $sheet->getStyle('A4:G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');//background
        $sheet->getStyle('A4:G'.$row)->applyFromArray($styleArray);//border
        $writer = new Xlsx($excel);
        $writer->save('php://output');
    }

    public static function rekapPerangkatan($npsn, $angkatan, $keterserapan)
    {
        if (Gate::allows('roleAdminOpeartor')) {
            return Siswa::where('siswa_sekolah', $npsn)->where('siswa_angkatan', $angkatan)->whereHas('keterserapans', function ($query) use ($keterserapan) {
                $query->where('keterserapan_id', $keterserapan);
            })->count();
        } else {
            abort(403);
        }
    }

    public static function rekapPerangkatanTotal($npsn, $angkatan)
    {
        if (Gate::allows('roleAdminOpeartor')) {
            return Siswa::where('siswa_sekolah', $npsn)->where('siswa_angkatan', $angkatan)->has('keterserapans')->count();
        } else {
            abort(403);
        }
    }

    public function rekapSemuaSekolah()
    {
        $this->authorize('roleAdmin');
        $sekolah = Sekolah::all();
        if ($sekolah->count() <= 0) {
            return \redirect()->route('rekap.index')->with('status_semua_sekolah', 'Rekap Semua SMK Trenggalek Tidak Tersedia');
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode('rekap_semua_'.date("Y_m_d_h_i_sa").'.xlsx').'"');
        $row = 5;
        $i = 0;
        $totalKeterserpan = 0;
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setCellValue('A2', 'Data Keterserapan Lulusan SMK Kab. Trenggalek ');
        $sheet->mergeCells("C4:G4");//merger cell jumlah keterserapn
        $sheet->setCellValue('C4', "Jumlah");
        $sheet->mergeCells("A4:A5");//merger cell No
        $sheet->setCellValue('A4', "NO");
        $sheet->mergeCells("B4:B5");//merger cell nama sekolah
        $sheet->setCellValue('B4', "Sekolah");
        $sheet->setCellValue('C5', "Bekerja");
        $sheet->setCellValue('D5', "Melanjutkan");
        $sheet->setCellValue('E5', "Wiraswasta");
        $sheet->setCellValue('F5', "Belum Terserap");
        $sheet->setCellValue('G5', "Total");
        foreach ($sekolah as $rekap) {
            $i++;
            $row++;
            $total1 += $this->rekapSemua($rekap->npsn, '1');
            $total2 += $this->rekapSemua($rekap->npsn, '2');
            $total3 += $this->rekapSemua($rekap->npsn, '3');
            $total4 += $this->rekapSemua($rekap->npsn, '4');
            $totalKeterserpan += $this->rekapSemuaTotal($rekap->npsn);
            $sheet->setCellValue('A'.$row, $i);
            $sheet->setCellValue('B'.$row, $rekap->sekolah_nama);
            $sheet->setCellValue('C'.$row, $this->rekapSemua($rekap->npsn, '1'));
            $sheet->setCellValue('D'.$row, $this->rekapSemua($rekap->npsn, '2'));
            $sheet->setCellValue('E'.$row, $this->rekapSemua($rekap->npsn, '3'));
            $sheet->setCellValue('F'.$row, $this->rekapSemua($rekap->npsn, '4'));
            $sheet->setCellValue('G'.$row, $this->rekapSemuaTotal($rekap->npsn));
        }
        //set auto width
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        //grendtotal
        $sheet->setCellValue('B'.$row+=1, "Total");
        $sheet->setCellValue('C'.$row, $total1." (".$total1*100/$totalKeterserpan."%)");
        $sheet->setCellValue('D'.$row, $total2." (".$total2*100/$totalKeterserpan."%)");
        $sheet->setCellValue('E'.$row, $total3." (".$total3*100/$totalKeterserpan."%)");
        $sheet->setCellValue('F'.$row, $total4." (".$total4*100/$totalKeterserpan."%)");
        $sheet->setCellValue('G'.$row, $totalKeterserpan);
        //grendtotal
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        // $styleHeader = [
        //     'fill' => [
        //         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        //         'rotation' => 90,
        //         'startColor' => [
        //             'argb' => '#DFDFDF',
        //         ],
        //     ],
        // ];
        $sheet->getStyle('A4:G5')->getAlignment()->setHorizontal('center');//aligment text,rata tengah
        $sheet->getStyle('A4:G5')->getAlignment()->setVertical('center');//aligment text,rata tengah
        // $sheet->getStyle('A4:G5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);//font color
        $sheet->getStyle('A4:G5')->getFont()->setBold(true);//bold font
        $sheet->getStyle('A4:G5')->getFont()->setSize(13);//font size
        $sheet->getStyle('A4:G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');//background
        $sheet->getStyle('A4:G'.$row)->applyFromArray($styleArray);//border
        $writer = new Xlsx($excel);
        $writer->save('php://output');
    }
    public static function rekapSemua($npsn, $id)
    {
        if (Gate::allows('roleAdmin')) {
            return Siswa::with('keterserapans')->where('siswa_sekolah', $npsn)->whereHas('keterserapans', function ($query) use ($id) {
                $query->where("keterserapan_id", $id);
            })->count();
        } else {
            abort(403);
        }
    }

    public static function rekapSemuaTotal($npsn)
    {
        if (Gate::allows('roleAdmin')) {
            return Siswa::where('siswa_sekolah', $npsn)->has('keterserapans')->count();
        } else {
            abort(403);
        }
    }
}
