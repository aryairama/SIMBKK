<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\User;
use App\Sekolah;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
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
    public function index()
    {
        return \view('user.index');
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
    public function store(UserRequest $request)
    {
        if ($request->roles == "admin") {
            $user = new User();
            $user->username = $request->username;
            $user->password = \Hash::make($request->password);
            $user->roles = $request->roles;
            $user->save();
        } elseif ($request->roles == "operator_sekolah") {
            $user = new User();
            $user->npsn = $request->npsn;
            $user->username = $request->username;
            $user->password = \Hash::make($request->password);
            $user->roles = $request->roles;
            $user->save();
        }
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
        try {
            $user = User::findOrFail(decrypt($id));
            if ($user->roles == "admin") {
                $user_collection = collect($user);
                $user_collection->except(['id']);
                $user_collection->put('id', $id);
            } elseif ($user->roles == "operator_sekolah") {
                $user_collection = collect($user);
                $user_collection->except(['id']);
                $user_collection->put('id', $id);
                $user_collection->put('sekolah', $user->sekolahs->sekolah_nama);
            }
            return $user_collection;
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
    public function update(UserRequest $request, $id)
    {
        try {
            $user = User::findOrFail(decrypt($id));
            if ($request->username) {
                $user->username = $request->username;
            }
            if ($request->password) {
                $user->password = \Hash::make($request->password);
            }
            if ($request->password || $request->username) {
                $user->save();
                return "update";
            }
        } catch (DecryptException $th) {
            throw $th;
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
        try {
            $user = User::findOrFail(decrypt($id));
            $user->delete();
            return "delete";
        } catch (DecryptException $th) {
            throw $th;
        }
    }

    public function datatableOperator(Request $request)
    {
        $operator = User::with('sekolahs')->where('roles', '=', 'operator_sekolah');
        if ($request->ajax()) {
            return DataTables::of($operator)
            ->addIndexColumn()
            ->addColumn('action', function ($operator) {
                $btn = '<a href="javascript:void(0)" class="edit badge badge-success" onclick="editForm(\''.encrypt($operator->id).'\')">Edit</a>
                    <a href="javascript:void(0)" class="delete badge badge-danger" onclick="deleteForm(\''.encrypt($operator->id).'\')">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function datatableAdmin(Request $request)
    {
        $admin = User::where('roles', '=', 'admin');
        if ($request->ajax()) {
            return DataTables::of($admin)
            ->addIndexColumn()
            ->addColumn('action', function ($admin) {
                $btn = '<a href="javascript:void(0)" class="edit badge badge-success" onclick="editForm(\''.encrypt($admin->id).'\')">Edit</a>
                    <a href="javascript:void(0)" class="delete badge badge-danger" onclick="deleteForm(\''.encrypt($admin->id).'\')">Delete</a>';
                return $btn;
            })->rawColumns(['action'])
            ->make(true);
        }
    }

    public function selectNpsn(Request $request)
    {
        $data = [];
        $search = $request->q;
        $data = \App\Sekolah::with('userss')->select("npsn", "sekolah_nama")
                    ->where('sekolah_nama', 'LIKE', "%$search%")->whereDoesntHave('userss', function ($query) {
                        $query->where('roles', 'admin');
                    })->doesntHave('userss')
                    ->get();
        return response()->json($data);
    }
}
