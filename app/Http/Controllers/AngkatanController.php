<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AngkatanRequest;
use App\Angkatan;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class AngkatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Gate::allows('roleAdminOpeartor')) {
            $angkatan = \DB::table('angkatan')->select(['angkatan_id','angkatan_ket']);
            if ($request->ajax()) {
                return DataTables::of($angkatan)
                ->addIndexColumn()
                ->addColumn('action', function ($angkatan) {
                    if (Gate::allows('roleOperatorSekolah')) {
                        $btn = '<a href="javascript:void(0)" class="edit badge badge-success" onclick="editForm('.$angkatan->angkatan_id.')">Edit</a>';
                    } elseif (Gate::allows('roleAdmin')) {
                        $btn = '<a href="javascript:void(0)" class="edit badge badge-success" onclick="editForm('.$angkatan->angkatan_id.')">Edit</a>
                    <a href="javascript:void(0)" class="delete badge badge-danger" onclick="deleteForm('.$angkatan->angkatan_id.')">Delete</a>';
                    }
                    return $btn;
                })->rawColumns(['action'])
                ->make(true);
            }
            return view('angkatan.index');
        } else {
            abort(403);
        }
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
    public function store(AngkatanRequest $request)
    {
        $this->authorize('roleAdminOpeartor');
        $angkatan = new Angkatan();
        $angkatan->angkatan_ket = $request->angkatan_ket;
        $angkatan->save();
        return "save";
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
        $this->authorize('roleAdminOpeartor');

        $angkatan = Angkatan::findOrFail($id);
        return $angkatan;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AngkatanRequest $request, $id)
    {
        $this->authorize('roleAdminOpeartor');
        $angkatan = Angkatan::findOrFail($id);
        $angkatan->angkatan_ket = $request->angkatan_ket;
        $angkatan->save();
        return "update";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('roleAdmin');
        $angkatan = Angkatan::findOrFail($id);
        $checkAngkatan = \App\Siswa::where('siswa_angkatan', $id)->get();
        $checkAngkatan = \count($checkAngkatan);
        if ($checkAngkatan > 0) {
            return \Response::json(403);
        } else {
            $angkatan->delete();
            return "delete";
        }
    }
}
