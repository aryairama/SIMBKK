<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SiswaRequest;
use App\Siswa;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('roleAdminOpeartor');
        if (Gate::allows('roleOperatorSekolah')) {
            $npsn = \Auth::user()->npsn;
            $siswa = Siswa::with('sekolahs')->with('komlis')->with('keterserapans')->with('angkatans')->has('sekolahs')->has('komlis')->has('keterserapans')->has('angkatans')->whereHas('sekolahs', function ($query) use ($npsn) {
                $query->where('npsn', $npsn);
            });
        } elseif (Gate::allows('roleAdmin')) {
            $siswa = Siswa::with('sekolahs')->with('komlis')->with('keterserapans')->with('angkatans')->has('sekolahs')->has('komlis')->has('keterserapans')->has('angkatans');
        }
        if ($request->ajax()) {
            return DataTables::of($siswa)
            ->addIndexColumn()
            ->addColumn('action', function ($siswa) {
                if (Gate::allows('roleOperatorSekolah')) {
                    $btn = '<a href="javascript:void(0)" class="edit badge badge-success" onclick="editForm(\''.encrypt($siswa->siswa_id).'\')">Edit</a>
                    <a href="javascript:void(0)" class="delete badge badge-danger" onclick="deleteForm(\''.encrypt($siswa->siswa_id).'\')">Delete</a>';
                    return $btn;
                } else {
                    return "No action";
                }
            })->rawColumns(['action'])
            ->make(true);
        }
        return view('siswa.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SiswaRequest $request)
    {
        if (Gate::allows('roleOperatorSekolah')) {
            $siswa = new Siswa();
            $siswa->nisn = $request->nisn;
            $siswa->siswa_nama = $request->siswa_nama;
            $siswa->siswa_sekolah = \Auth::user()->sekolahs->npsn;
            $siswa->siswa_angkatan = $request->siswa_angkatan;
            $siswa->tempat_lahir = $request->tempat_lahir;
            $siswa->tanggal_lahir = $request->tanggal_lahir;
            $siswa->siswa_jk = $request->siswa_jk;
            $siswa->siswa_komli = $request->siswa_komli;
            $siswa->siswa_prestasi = $request->siswa_prestasi;
            $siswa->siswa_keterserapan = $request->siswa_keterserapan;
            $siswa->keterangan = $request->keterangan;
            $siswa->save();
            return "save";
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Gate::allows('roleOperatorSekolah')) {
            $siswa = Siswa::findOrFail(decrypt($id));
            $siswa_collection = [
            'siswa_id' => $id,
            'nisn' => $siswa->nisn,
            'siswa_nama' => $siswa->siswa_nama,
            'siswa_angkatan' => [
                'angkatan_id' => $siswa->angkatans->angkatan_id,
                'angkatan_ket' => $siswa->angkatans->angkatan_ket
            ],
            'tempat_lahir' => $siswa->tempat_lahir,
            'tanggal_lahir' => $siswa->tanggal_lahir,
            'siswa_jk' => $siswa->siswa_jk,
            'siswa_komli' => [
                'komli_id' => $siswa->komlis->komli_id,
                'komli_nama' => $siswa->komlis->komli_nama
            ],
            'siswa_prestasi' => $siswa->siswa_prestasi,
            'siswa_keterserapan' => [
                'keterserapan_id' => $siswa->keterserapans->keterserapan_id,
                'keterserapan_nama' => $siswa->keterserapans->keterserapan_nama
            ],
            'keterangan' => $siswa->keterangan
        ];
            return $siswa_collection;
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SiswaRequest $request, $id)
    {
        if (Gate::allows('roleOperatorSekolah')) {
            try {
                $siswa = Siswa::findOrFail(decrypt($id));
                $siswa->nisn = $request->nisn;
                $siswa->siswa_nama = $request->siswa_nama;
                $siswa->siswa_angkatan = $request->siswa_angkatan;
                $siswa->tempat_lahir = $request->tempat_lahir;
                $siswa->tanggal_lahir = $request->tanggal_lahir;
                $siswa->siswa_jk = $request->siswa_jk;
                $siswa->siswa_komli = $request->siswa_komli;
                $siswa->siswa_prestasi = $request->siswa_prestasi;
                $siswa->siswa_keterserapan = $request->siswa_keterserapan;
                $siswa->keterangan = $request->keterangan;
                $siswa->save();
                return "update";
            } catch (DecryptException $th) {
                throw $th;
            }
        } else {
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Gate::allows('roleOperatorSekolah')) {
            try {
                $siswa = Siswa::findOrFail(decrypt($id));
                $siswa->delete();
                return "delete";
            } catch (DecryptException $th) {
                throw $th;
            }
        } else {
            abort(403);
        }
    }

    public function sekolahNama(Request $request)
    {
        $this->authorize('roleAdminOpeartor');
        $data = [];
        $search = $request->q;
        if ($request->ajax()) {
            $data = \App\Sekolah::with('userss')->select("npsn", "sekolah_nama")
                    ->where('sekolah_nama', 'LIKE', "%$search%")->whereDoesntHave('userss', function ($query) {
                        $query->where('roles', 'admin');
                    })
                    ->get();
            return response()->json($data);
        }
    }

    public function angkatanNama(Request $request)
    {
        $this->authorize('roleAdminOpeartor');
        $data = [];
        $search = $request->q;
        if ($request->ajax()) {
            $data = \App\Angkatan::where('angkatan_ket', 'LIKE', "%$search%")->get();
            return \response()->json($data);
        }
    }

    public function komliNama(Request $request)
    {
        $this->authorize('roleAdminOpeartor');
        $data = [];
        $search = $request->q;
        if ($request->ajax()) {
            $data = \App\Komli::where('komli_nama', 'LIKE', "%$search%")->get();
            return response()->json($data);
        }
    }

    public function keterserapanNama(Request $request)
    {
        $this->authorize('roleOperatorSekolah');
        $data = [];
        $search = $request->q;
        if ($request->ajax()) {
            $data = \App\Keterserapan::where('keterserapan_nama', 'LIKE', "%$search%")->get();
            return \response()->json($data);
        }
    }
}
