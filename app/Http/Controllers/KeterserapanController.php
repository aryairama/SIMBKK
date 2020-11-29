<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\KeterserapanRequest;
use App\Keterserapan;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class KeterserapanController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('roleAdminOpeartor');
        $dataKeterserapan = Keterserapan::orderBy('keterserapan_id', 'ASC');
        if ($request->ajax()) {
            return DataTables::of($dataKeterserapan)
                ->addIndexColumn()
                ->addColumn('action', function ($dataKeterserapan) {
                    if (Gate::allows('roleAdmin')) {
                        $btn = '<a href="javascript:void(0)" class="edit badge badge-success" onclick="editForm('.$dataKeterserapan->keterserapan_id.')">Edit</a>
                    <a href="javascript:void(0)" class="delete badge badge-danger" onclick="deleteForm('.$dataKeterserapan->keterserapan_id.')">Delete</a>';
                        return $btn;
                    }
                    return "No action";
                })->rawColumns(['action'])
                ->make(true);
        }
        return view('keterserapan.index');
    }

    public function create()
    {
        //
    }

    public function store(KeterserapanRequest $request)
    {
        $this->authorize('roleAdmin');
        $keterserapan = new Keterserapan();
        $keterserapan->keterserapan_nama = $request->keterserapan_nama;
        $keterserapan->save();
        return "save";
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $this->authorize('roleAdmin');
        $keterserapan = Keterserapan::findOrFail($id);
        return $keterserapan;
    }

    public function update(KeterserapanRequest $request, $id)
    {
        $this->authorize('roleAdmin');
        $keterserapan = Keterserapan::findOrFail($id);
        $keterserapan->keterserapan_nama = $request->keterserapan_nama;
        $keterserapan->save();
        return "update";
    }


    public function destroy($id)
    {
        $this->authorize('roleAdmin');
        $keterserpan = Keterserapan::findOrFail($id);
        $checkKeterserapan = \App\Siswa::where('siswa_keterserapan', $id)->get();
        $checkKeterserapan = \count($checkKeterserapan);
        if ($checkKeterserapan > 0) {
            return \Response::json(403);
        } else {
            $keterserpan->delete();
            return "delete";
        }
    }
}
