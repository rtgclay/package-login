<?php

namespace Smpl\Login\Http\Controllers;

use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(Request $request)
    {
     return view('index');  
    }

    protected function guard()
    {
        return Auth::guard(config('nova.guard'));
    }

    public function redirectPath()
    {
        return Nova::url(Nova::$initialPath);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect()->intended($this->redirectPath());
    }
}
