<?php

namespace App\Http\Requests;

use App\Komli;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class KomliRequest extends FormRequest
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
        if ($request->komli_id) {
            $komli = Komli::findOrFail($request->komli_id);
            if ($request->komli_nama == $komli->komli_nama) {
                return [
                    'komli_nama' => 'required|min:3|max:255'
                ];
            } else {
                return [
                    'komli_nama' => 'required|min:3|max:255|unique:komli,komli_nama'
                ];
            }
        } else {
            return [
                'komli_nama' => 'required|min:3|max:255|unique:komli,komli_nama'
            ];
        }
    }

    public function messages()
    {
        return [
            'komli_nama.unique' => 'Nama komli sudah terdaftar,silahkan gunakan Nama komli yang lain'
        ];
    }
}
