<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authcontroller extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function proses (Request $request) {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if(Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('dashboard');
        }
        return back()->withErrors([
            'email'=>'email atau password salah'
        ]);     
    }

    public function logout(Request $request) {
	Auth::logout();
	$request->session()->invalidate();
	$request->session()->regenerateToken();

	return redirect()->route('login');
    }
}
