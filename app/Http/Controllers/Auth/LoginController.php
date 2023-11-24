<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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
    public function redirectTo()
    {
        $role = Auth::user()->role;
        if ($role == 'admin' || $role == 'karyawan') {
            return '/salon';
        } else if ($role == 'pelanggan') {
            return '/pelanggan';
        } 
        // else if ($role == 'superadmin' || $role == 'admin') {
        //     if (tenant('tenant_status') != 'nonaktif') {
        //         if (Auth::user()->adminTenant->status == 'aktif') {
        //             return '/admin';
        //         } else {
        //             Auth::logout();
        //         }
        //     }
        // }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
