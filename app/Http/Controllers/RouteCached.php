<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RouteCached extends Controller
{
    public function directLogin(){
        return redirect()->route('login');
    }

    public function importFormatExcel(){
        return response()->download('data_siswa/format.xlsx');
    }
}
