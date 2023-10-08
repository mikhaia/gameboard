<?php

namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;

class AuthController
{
    public function signup(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $request->input('email');

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Happy to see you again!');
        }

        if (User::where('email', $email)->first()) {
            return redirect()->back()->withErrors(['msg'=>'E-mail already registered.<br>Please use recovery password']);
        }

        User::create([
            'email' => $email,
            'name' => substr($email, 0, strpos($email, '@')),
            'password' => bcrypt($request->input('password'))
        ]);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Welcome to '.env('APP_NAME').'!<br>I hope you will love being here!');
        }
    }

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
