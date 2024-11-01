<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home')->with('success', 'Giriş başarılı!');
        }

        return back()->withErrors([
            'email' => 'E-posta adresi veya şifre yanlış.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Çıkış başarılı!');
    }
}
