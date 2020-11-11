<?php

namespace App\Http\Requests;

use App\Siswa;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class SiswaRequest extends FormRequest
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
        if ($request->siswa_id) {
            $siswa = Siswa::findOrFail(decrypt($request->siswa_id));
            if ($request->nisn == $siswa->nisn) {
                return [
                    'nisn' => 'required|min:10|max:10|digits:10',
                    'siswa_nama' => 'required|min:5|max:255',
                    'siswa_angkatan' => 'required',
                    'tempat_lahir' => 'required|max:225',
                    'tanggal_lahir' => 'required|min:10|max:10',
                    'siswa_jk' => 'required',
                    'siswa_komli' => 'required',
                    'siswa_prestasi' => 'nullable',
                    'siswa_keterserapan' => 'required',
                    'keterangan' => 'nullable',
                ];
            } else {
                return [
                    'nisn' => 'required|min:10|max:10|digits:10|unique:siswa,nisn',
                    'siswa_nama' => 'required|min:5|max:255',
                    'siswa_angkatan' => 'required',
                    'tempat_lahir' => 'required|max:225',
                    'tanggal_lahir' => 'required|min:10|max:10',
                    'siswa_jk' => 'required',
                    'siswa_komli' => 'required',
                    'siswa_prestasi' => 'nullable',
                    'siswa_keterserapan' => 'required',
                    'keterangan' => 'nullable',
                ];
            }
        } else {
            return [
                    'nisn' => 'required|min:10|max:10|digits:10|unique:siswa,nisn',
                    'siswa_nama' => 'required|min:5|max:255',
                    'siswa_angkatan' => 'required',
                    'tempat_lahir' => 'required|max:225',
                    'tanggal_lahir' => 'required|min:10|max:10',
                    'siswa_jk' => 'required',
                    'siswa_komli' => 'required',
                    'siswa_prestasi' => 'nullable',
                    'siswa_keterserapan' => 'required',
                    'keterangan' => 'nullable',
                ];
        }
    }

    public function messages()
    {
        return [
            'nisn.unique' => 'NISN sudah terdaftar,silahkan gunakan NISN yang lain'
        ];
    }
}
