<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Sekolah;
use \App\User;
use Illuminate\Support\Facades\Gate;

class ProfileSekolahController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profileSekolah = Sekolah::findOrFail(\Auth::user()->npsn);
        return view('user.update', compact('profileSekolah'));
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
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $valid = \Validator::make($request->all(), [
            'old_password' => 'required|min:8',
            'new_password' => 'required|required_with:confirm_password|same:confirm_password|min:8|different:old_password',
            'confirm_password' => 'required|min:8'
        ]);
        if ($valid->fails()) {
            return \redirect()->route('profile.index')->with('status', 'Ubah password gagal')->with('type', 'danger')->withErrors($valid)
            ->withInput();
        }
        if (\Hash::check($request->old_password, \Auth::user()->password)) {
            $updateProfile = User::findOrFail(\Auth::user()->id);
            $updateProfile->password = \Hash::make($request->new_password);
            $updateProfile->save();
            return \redirect()->route('profile.index')->with('status', 'Password berhasil di update')->with('type', 'success');
        } else {
            return \redirect()->route('profile.index')->with('status', 'Password tidak cocok dengan password lama')->with('type', 'danger');
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
        //
    }
}
