<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\KomliRequest;
use App\Komli;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class KomliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('roleAdminOpeartor');
        $komli = Komli::orderBy('komli_id', 'ASC');
        if ($request->ajax()) {
            return DataTables::of($komli)
                ->addIndexColumn()
                ->addColumn('action', function ($komli) {
                    if (Gate::allows('roleAdmin')) {
                        $btn = '<a href="javascript:void(0)" class="edit badge badge-success" onclick="editForm('.$komli->komli_id.')">Edit</a>
                    <a href="javascript:void(0)" class="delete badge badge-danger" onclick="deleteForm('.$komli->komli_id.')">Delete</a>';
                        return $btn;
                    }
                    return "No action";
                })->rawColumns(['action'])
                ->make(true);
        }
        return view('komli.index');
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
    public function store(KomliRequest $request)
    {
        $this->authorize('roleAdmin');
        $komli = new Komli();
        $komli->komli_nama = $request->komli_nama;
        $komli->save();
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
        $this->authorize('roleAdmin');
        $komli = Komli::findOrFail($id);
        return $komli;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KomliRequest $request, $id)
    {
        $this->authorize('roleAdmin');
        $komli = Komli::findOrFail($id);
        $komli->komli_nama = $request->komli_nama;
        $komli->save();
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
        $komli = Komli::findOrFail($id);
        $checkKomli = \App\Siswa::where('siswa_komli', $id)->get();
        $checkKomli = \count($checkKomli);
        if ($checkKomli > 0) {
            return \Response::json(403);
        } else {
            $komli->delete();
            return "delete";
        }
    }
}
