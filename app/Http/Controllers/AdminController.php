<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function logintampil() {
        return view('admin.login');
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if(Auth::guard('admin')->attempt($credentials)){
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau pasword salah']);
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function dashboard(){
        return view('admin.dashboard');
    }
}
