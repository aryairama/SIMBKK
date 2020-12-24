<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function validateLogin(Request $request)
    {
        $request->validate([
            'npsn' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'npsn' => [trans('auth.failed')],
        ]);
    }

    public function authenticated(Request $request, $user)
    {
        return \redirect()->route('dashboard');
    }

    public function username()
    {
        $login = request()->input('npsn');
        if (is_numeric($login)) {
            $field = 'npsn';
        } else {
            $field = 'username';
        }
        request()->merge([$field => $login]);
        return $field;
    }
}
