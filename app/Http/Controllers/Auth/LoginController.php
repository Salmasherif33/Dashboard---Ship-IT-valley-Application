<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web-admin')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('web-admin');
    }

    // public function login(Request $request)
    // {
    //     $this->validateLogin($request);

    //     if (
    //         method_exists($this, 'hasTooManyLoginAttempts') &&
    //         $this->hasTooManyLoginAttempts($request)
    //     ) {
    //         $this->fireLockoutEvent($request);

    //         return $this->sendLockoutResponse($request);
    //     }

    //     if ($this->guard('web-admin')->attempt($request->only('email', 'password'))) {
    //         $admin = Admin::where('email', '=', $request->get('email'))->first();
    //         if ($admin->type = "admin") {
    //             return redirect('/home');
    //         }
    //         if ($admin->type = "company") {
    //             return redirect('/home');
    //         }
    //     }
    // }
}
