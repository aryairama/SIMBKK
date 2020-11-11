<?php

namespace App\Http\Requests;

use App\Keterserapan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class KeterserapanRequest extends FormRequest
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
        if ($request->keterserapan_id) {
            $keterserapan = Keterserapan::findOrFail($request->keterserapan_id);
            if ($request->keterserapan_nama == $keterserapan->keterserapan_nama) {
                return [
                    'keterserapan_nama' => 'required|min:3|max:255'
                ];
            } else {
                return [
                    'keterserapan_nama' => 'required|min:3|max:255|unique:keterserapan,keterserapan_nama'
                ];
            }
        } else {
            return [
                'keterserapan_nama' => 'required|min:3|max:255|unique:keterserapan,keterserapan_nama'
            ];
        }
    }

    public function messages()
    {
        return [
            'keterserapan_nama.unique' => 'Nama Keterserapan sudah terdaftar,silahkan gunakan Nama Keterserapan yang lain'
        ];
    }
}
