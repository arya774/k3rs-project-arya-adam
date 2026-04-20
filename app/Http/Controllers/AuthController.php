<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // TAMPIL LOGIN
    public function loginForm()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required'
        ]);

        // cari user berdasarkan NIP
        $user = User::where('nip', $request->nip)->first();

        // 🔥 JIKA USER BELUM ADA → AUTO REGISTER
        if (!$user) {
            $user = User::create([
                'name' => 'User ' . $request->nip,
                'nip' => $request->nip,
                'password' => bcrypt($request->password)
            ]);

            Auth::login($user);
            return redirect()->route('inspeksi.dashboard');
        }

        // 🔐 LOGIN NORMAL (PAKAI AUTH::ATTEMPT)
        if (Auth::attempt([
            'nip' => $request->nip,
            'password' => $request->password
        ])) {
            $request->session()->regenerate(); // keamanan session
            return redirect()->route('inspeksi.dashboard');
        }

        // ❌ PASSWORD SALAH
        return back()->with('error', 'Password salah')->withInput();
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}