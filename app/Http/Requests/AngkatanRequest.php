<?php

namespace App\Http\Requests;

use App\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class AngkatanRequest extends FormRequest
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
        if ($request->angkatan_id) {
            $angkatan = Angkatan::findOrFail($request->angkatan_id);
            if ($request->angkatan_ket == $angkatan->angkatan_ket) {
                return [
                    'angkatan_ket' => 'required|min:9|max:9'
                ];
            } else {
                return [
                    'angkatan_ket' => 'required|min:9|max:9|unique:angkatan,angkatan_ket'
                ];
            }
        } else {
            return [
                'angkatan_ket' => 'required|min:9|max:9|unique:angkatan,angkatan_ket'
            ];
        }
    }

    public function messages()
    {
        return [
            'angkatan_ket.unique' => 'Tahun angkatan sudah terdaftar,silahkan gunakan tahun angkatan yang lain'
        ];
    }
}
