<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek apakah akun user aktif
            if (!$user->is_active) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan.');
            }

            // Cek apakah user memiliki peran Admin atau Agen
            if ($user->hasRole('Admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('Agen')) {
                return redirect()->route('agen.dashboard');
            }

            return redirect()->route('home')->with('error', 'Role tidak ditemukan.');
        }

        return redirect()->back()->with('error', 'Username atau password salah.');
    }



    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
