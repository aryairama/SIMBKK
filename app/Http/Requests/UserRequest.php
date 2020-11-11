<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        if ($request->roles) {
            if ($request->roles == "admin") {
                return [
                    'username' => 'required|min:8|max:10|unique:users,username',
                    'password' => 'required|min:8|max:255',
                    'roles' => 'required'
            ];
            } elseif ($request->roles == "operator_sekolah") {
                return [
                    'username' => 'required|min:8|max:10|unique:users,username',
                    'password' => 'required|min:8|max:255',
                    'roles' => 'required',
                    'npsn' => 'required'
                ];
            }
        } elseif ($request->user_id) {
            $user = User::findOrFail(decrypt($request->user_id));
            if ($request->username == $user->username) {
                return [
                'username' => 'required|min:8|max:10',
                'password' => 'nullable|min:8|max:255',
            ];
            } elseif ($request->username != $user->username) {
                return [
                'username' => 'required|min:8|max:10|unique:users,username',
                'password' => 'nullable|min:8|max:255',
            ];
            }
        }
    }

    public function messages()
    {
        return [
            'username.unique' => 'Username sudah terdaftar,silahkan gunakan Username yang lain'
        ];
    }
}
