<?php

namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Happy to see you again!');
        }
        return redirect()->back()->withErrors(['msg'=>'E-mail or password is incorrect']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'See you next time!');
    }

    public function index()
    {
        return Inertia::render('Auth');
    }
}
