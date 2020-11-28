<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sekolah;
use App\Siswa;
use Auth;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ExportExcelController $ExportExcelController)
    {
        $this->ExportExcelController = $ExportExcelController;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Gate::allows('roleAdmin')) {
            $sekolah = Sekolah::all();
            $countSekolah = Sekolah::all()->count();
            $countSiswa = Siswa::all()->count();
            $countMale = Siswa::where('siswa_jk', 'L')->get()->count();
            $countFemale = Siswa::where('siswa_jk', 'P')->get()->count();
            //chartjs keterserapan
            $dataSekolah = Sekolah::orderBy('npsn', 'ASC')->get();
            $dSekolah = array();
            $ktr1 = array();
            $ktr2 = array();
            $ktr3 = array();
            $ktr4 = array();
            foreach ($dataSekolah as $key => $data) {
                $dSekolah[$key] = $data->sekolah_nama;
                $ktr1[$key] = $this->ExportExcelController->rekapSemua($data->npsn, 1);
                $ktr2[$key] = $this->ExportExcelController->rekapSemua($data->npsn, 2);
                $ktr3[$key] = $this->ExportExcelController->rekapSemua($data->npsn, 3);
                $ktr4[$key] = $this->ExportExcelController->rekapSemua($data->npsn, 4);
            }
            $chartKeterserapan = app()->chartjs
        ->name('chartKeterserapan')
        ->type('bar')
        ->size(['width' => 400, 'height' => 220])
        ->labels($dSekolah)
        ->datasets([
            [
                "label" => "Bekerja",
                'backgroundColor' => "#ffa534",
                'data' => $ktr1,
            ],
            [
                "label" => "Melanjutkan",
                'backgroundColor' => "#716aca",
                'data' => $ktr2,
            ],
            [
                "label" => "Wiraswasta",
                'backgroundColor' => "#177dff",
                'data' => $ktr3,
            ],
            [
                "label" => "Belum Terserap",
                'backgroundColor' => "#f3545d",
                'data' => $ktr4,
            ]
        ])
        ->options([]);
            $chartKeterserapan->optionsRaw([
            'responsive'=> true,
            'maintainAspectRatio' => true,
            'legend' => [
                'display' => true,
                'labels' => [
                    'fontColor' => '#000'
                ]
            ],
            'scales' => [
                'xAxes' => [
                    [
                        'stacked' => true,
                        'gridLines' => [
                            'display' => false
                        ]
                    ]
                        ],
                'yAxes' => [
                    [
                        'stacked' => true,
                        'gridLines' => [
                            'display' => true
                        ]
                    ]
                            ],
            ]
        ]);
            //chartjs sekolah
            $dSiswa = array();
            $cSiswa = array();
            $dWarna = array();
            foreach ($dataSekolah as $key => $data) {
                $dSiswa[$key] = $data->sekolah_nama;
                $cSiswa[$key] = $this->countSiswa($data->npsn);
                $dWarna[$key] = $this->random_color();
            }
            $chartSiswa = app()->chartjs
        ->name('chartSiswa')
        ->type('bar')
        ->size(['width' => 400, 'height' => 220])
        ->labels($dSiswa)
        ->datasets([
            [
                "label" => "Jumlah Siswa",
                'backgroundColor' =>  $dWarna,
                'data' => $cSiswa,
            ],
        ])
        ->options([]);
            $chartSiswa->optionsRaw([
            'responsive'=> true,
            'maintainAspectRatio' => true,
            'legend' => [
                'display' => true,
                'labels' => [
                    'fontColor' => '#000'
                ]
            ],
            'scales' => [
                'xAxes' => [
                    [
                        'stacked' => false,
                        'gridLines' => [
                            'display' => false
                        ]
                    ]
                        ],
                'yAxes' => [
                    [
                        'stacked' => false,
                        'gridLines' => [
                            'display' => true
                        ]
                    ]
                            ],
            ]
        ]);
            //count keterserapan
            $countBekerja = Siswa::has('sekolahs')->has('keterserapans')
            ->whereHas('keterserapans', function ($query) {
                $query->where('keterserapan_id', 1);
            })->get()->count();
            $countMelanjutkan = Siswa::has('sekolahs')->has('keterserapans')
            ->whereHas('keterserapans', function ($query) {
                $query->where('keterserapan_id', 2);
            })->get()->count();
            $countWiraswasta = Siswa::has('sekolahs')->has('keterserapans')
            ->whereHas('keterserapans', function ($query) {
                $query->where('keterserapan_id', 3);
            })->get()->count();
            $countBelumTerserap = Siswa::has('sekolahs')->has('keterserapans')
            ->whereHas('keterserapans', function ($query) {
                $query->where('keterserapan_id', 4);
            })->get()->count();
            return view(
                'home',
                compact(
                    'countSekolah',
                    'countSiswa',
                    'countMale',
                    'countFemale',
                    'countBekerja',
                    'countMelanjutkan',
                    'countWiraswasta',
                    'countBelumTerserap',
                    'chartKeterserapan',
                    'chartSiswa'
                )
            );
        } elseif (Gate::allows('roleOperatorSekolah')) {
            $npsn = Auth::user()->npsn;
            //chart angkatan persekolah
            $dAngkatan = array();
            $dSekolah = array();
            $dWarna = array();
            $angkatan = \App\Angkatan::all();
            foreach ($angkatan as $key => $value) {
                $dSekolah[$key] = $value->angkatan_ket;
                $dAngkatan[$key] = $this->countSiswaPerAngkatanPerSekolah($npsn, $value->angkatan_id);
                $dWarna[$key] = $this->random_color();
            }

            $chartSiswaPerangkatan = app()->chartjs
        ->name('chartSiswaPerangkatan')
        ->type('doughnut')
        ->size(['width' => 400, 'height' => 220])
        ->labels($dSekolah)
        ->datasets([
            [
                "label" => "Jumlah Siswa",
                'backgroundColor' => $dWarna,
                'data' => $dAngkatan,
            ],
        ])
        ->options([]);
            $chartSiswaPerangkatan->optionsRaw([
            'responsive'=> true,
            'maintainAspectRatio' => true,
            'legend' => [
                'display' => true,
                'labels' => [
                    'fontColor' => '#000'
                ]
            ],
            'scales' => [
                'xAxes' => [
                    [
                        'stacked' => false,
                        'gridLines' => [
                            'display' => false
                        ]
                    ]
                        ],
                'yAxes' => [
                    [
                        'stacked' => false,
                        'gridLines' => [
                            'display' => true
                        ]
                    ]
                            ],
            ]
        ]);
            //count siswa
            $countSiswa = Siswa::has('sekolahs')->whereHas('sekolahs', function ($query) use ($npsn) {
                $query->where('npsn', $npsn);
            })->get()->count();
            $countMale =  Siswa::has('sekolahs')->whereHas('sekolahs', function ($query) use ($npsn) {
                $query->where('npsn', $npsn);
            })->where('siswa_jk', 'L')->get()->count();
            $countFemale = Siswa::has('sekolahs')->whereHas('sekolahs', function ($query) use ($npsn) {
                $query->where('npsn', $npsn);
            })->where('siswa_jk', 'P')->get()->count();
            //count keterserapan
            $countBekerja = Siswa::has('sekolahs')->has('keterserapans')
            ->whereHas('sekolahs', function ($query) use ($npsn) {
                $query->where('npsn', $npsn);
            })->whereHas('keterserapans', function ($query) {
                $query->where('keterserapan_id', 1);
            })->get()->count();
            $countMelanjutkan = Siswa::has('sekolahs')->has('keterserapans')
            ->whereHas('sekolahs', function ($query) use ($npsn) {
                $query->where('npsn', $npsn);
            })->whereHas('keterserapans', function ($query) {
                $query->where('keterserapan_id', 2);
            })->get()->count();
            $countWiraswasta = Siswa::has('sekolahs')->has('keterserapans')
            ->whereHas('sekolahs', function ($query) use ($npsn) {
                $query->where('npsn', $npsn);
            })->whereHas('keterserapans', function ($query) {
                $query->where('keterserapan_id', 3);
            })->get()->count();
            $countBelumTerserap = Siswa::has('sekolahs')->has('keterserapans')
            ->whereHas('sekolahs', function ($query) use ($npsn) {
                $query->where('npsn', $npsn);
            })->whereHas('keterserapans', function ($query) {
                $query->where('keterserapan_id', 4);
            })->get()->count();
            return view(
                'home',
                compact('countSiswa', 'countMale', 'countFemale', 'countBekerja', 'countMelanjutkan', 'countWiraswasta', 'countBelumTerserap', 'chartSiswaPerangkatan')
            );
        } else {
            abort(403);
        }
    }
    public function countSiswa($npsn)
    {
        return Siswa::where('siswa_sekolah', $npsn)->get()->count();
    }
    public function countSiswaPerAngkatanPerSekolah($npsn, $angkatan_id)
    {
        return Siswa::where('siswa_sekolah', $npsn)->where('siswa_angkatan', $angkatan_id)->count();
    }

    public function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    public function random_color()
    {
        return "#".$this->random_color_part() .  $this->random_color_part() .  $this->random_color_part();
    }
}
