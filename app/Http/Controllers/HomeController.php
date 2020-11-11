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
                )
            );
        } elseif (Gate::allows('roleOperatorSekolah')) {
            $npsn = Auth::user()->npsn;
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
                compact('countSiswa', 'countMale', 'countFemale', 'countBekerja', 'countMelanjutkan', 'countWiraswasta', 'countBelumTerserap')
            );
        } else {
            abort(403);
        }
    }
}
