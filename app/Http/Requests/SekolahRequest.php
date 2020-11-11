<?php

namespace App\Http\Requests;

use App\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Encryption\DecryptException;

class SekolahRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        if ($request->id) {
            $sekolah = Sekolah::findOrFail(decrypt($request->id));
            if (($request->npsn == $sekolah->npsn) && ($request->sekolah_email == $sekolah->sekolah_email)) {
                return  [
            'npsn' => 'required|min:8|max:8|digits:8',
            'sekolah_nama' => 'required|min:5|max:255',
            'sekolah_kepsek' => 'required|min:5|max:255',
            'sekolah_email' => 'required|min:5|max:255|email',
            'kec' => 'required|min:5|max:255',
            'kab' => 'required|min:5|max:255',
            'kode_pos' => 'required|min:5|max:5|digits:5',
        ];
            } elseif ($request->npsn == $sekolah->npsn) {
                return  [
            'npsn' => 'required|min:8|max:8|digits:8',
            'sekolah_nama' => 'required|min:5|max:255',
            'sekolah_kepsek' => 'required|min:5|max:255',
            'sekolah_email' => 'required|min:5|max:255|email|unique:sekolah,sekolah_email',
            'kec' => 'required|min:5|max:255',
            'kab' => 'required|min:5|max:255',
            'kode_pos' => 'required|min:5|max:5|digits:5',
        ];
            } elseif ($request->sekolah_email == $sekolah->sekolah_email) {
                return  [
            'npsn' => 'required|min:8|max:8|digits:8|unique:sekolah,npsn',
            'sekolah_nama' => 'required|min:5|max:255',
            'sekolah_kepsek' => 'required|min:5|max:255',
            'sekolah_email' => 'required|min:5|max:255|email',
            'kec' => 'required|min:5|max:255',
            'kab' => 'required|min:5|max:255',
            'kode_pos' => 'required|min:5|max:5|digits:5',
        ];
            }
        } else {
            return  [
            'npsn' => 'required|min:8|max:8|digits:8|unique:sekolah,npsn',
            'sekolah_nama' => 'required|min:5|max:255',
            'sekolah_kepsek' => 'required|min:5|max:255',
            'sekolah_email' => 'required|min:5|max:255|email|unique:sekolah,sekolah_email',
            'kec' => 'required|min:5|max:255',
            'kab' => 'required|min:5|max:255',
            'kode_pos' => 'required|min:5|max:5|digits:5',
        ];
        }
    }

    public function messages()
    {
        return [
            "npsn.unique" => "NPSN sudah terdaftar,silahkan gunakan NSPN yang lain",
            "sekolah_email.unique" => "Email sudah terdaftar,silahkan gunakan email yang lain"
    ];
    }
}
