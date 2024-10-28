<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            if (auth()->user()->role == 1) {
                $request->session()->regenerate();
                return redirect()->route('dashboard_admin');
            } elseif (auth()->user()->role == 2) {
                $request->session()->regenerate();
                return redirect()->route('dashboard_kepsek');
            } elseif (auth()->user()->role == 3) {
                $request->session()->regenerate();
                return redirect()->route('dashboard_guru');
            }
        } else {
            return redirect('/')->with('loginError', 'Login failed!');
        }
    }

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function get_logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
