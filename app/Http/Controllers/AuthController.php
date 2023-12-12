<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        if(Auth::guard('pegawai')->attempt(['nik'=>$request->nik,'password'=>$request->password]))
        {
            return redirect('/dashboard');
        }else{
            return redirect('/')->with(['warning' => 'NIK atau Password Salah']);
        }
    }

    public function proseslogout(){
        if(Auth::guard('pegawai')->check()){
            Auth::guard('pegawai')->logout();
            return redirect('/');
        }
    }
}
