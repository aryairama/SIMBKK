<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SekolahRequest;
use App\Sekolah;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class SekolahController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Gate::allows('roleAdmin')) {
                return $next($request);
            }
            abort(403);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataSekolah = Sekolah::with('userss')
        ->whereDoesntHave('userss', function ($query) {
            $query->where('roles', 'admin');
        });
        if ($request->ajax()) {
            return Datatables::of($dataSekolah)
                ->addIndexColumn()
                ->addColumn('action', function ($dataSekolah) {
                    $btn = '<a href="javascript:void(0)" class="edit badge badge-success" onclick="editForm(\''.encrypt($dataSekolah->npsn).'\')">Edit</a>
                    <a href="javascript:void(0)" class="delete badge badge-danger" onclick="deleteForm(\''.encrypt($dataSekolah->npsn).'\')">Delete</a>';
                    return $btn;
                })->addColumn('alamat', function ($dataSekolah) {
                    return $dataSekolah->kec.",".$dataSekolah->kab;
                })->rawColumns(['action'])
                ->make(true);
        }
        return view('sekolah.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SekolahRequest $request)
    {
        $validated = $request->validated();
        $sekolah = new Sekolah();
        $sekolah->npsn = $request->npsn;
        $sekolah->sekolah_nama = $request->sekolah_nama;
        $sekolah->sekolah_kepsek = $request->sekolah_kepsek;
        $sekolah->sekolah_email = $request->sekolah_email;
        $sekolah->kec = $request->kec;
        $sekolah->kab = $request->kab;
        $sekolah->kode_pos = $request->kode_pos;
        $sekolah->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $sekolah = Sekolah::findOrFail(decrypt($id));
            $sekolah_collection = collect($sekolah);
            $sekolah_collection->put('id', encrypt($sekolah->npsn));
            return $sekolah_collection;
        } catch (DecryptException $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SekolahRequest $request)
    {
        $validated = $request->validated();
        try {
            $sekolah = Sekolah::findOrFail(decrypt($request->id));
        } catch (DecryptException $th) {
            return \Response::json(404);
        }
        if ($sekolah->npsn != $request->npsn) {
            $sekolah->npsn = $request->npsn;
        }
        $sekolah->sekolah_nama = $request->sekolah_nama;
        $sekolah->sekolah_kepsek = $request->sekolah_kepsek;
        if ($sekolah->sekolah_email != $request->sekolah_email) {
            $sekolah->sekolah_email = $request->sekolah_email;
        }
        $sekolah->kec = $request->kec;
        $sekolah->kab = $request->kab;
        $sekolah->kode_pos = $request->kode_pos;
        $sekolah->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $sekolah = Sekolah::findOrFail(decrypt($id));
        } catch (DecryptException $th) {
            return \Response::json(404);
        }
        $checkSekolah = \App\Siswa::where('siswa_sekolah', decrypt($id))->get();
        $checkSekolah = \count($checkSekolah);
        if ($checkSekolah > 0) {
            return \Response::json(403);
        } else {
            $sekolah->delete();
            return "delete";
        }
    }
}
