<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    /**
     * 🔥 LOGIN NIP (AUTO REGISTER)
     */
    public function login(Request $request)
    {
        // ✅ Validasi input
        $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $nip = $request->nip;
        $password = $request->password;

        // 🔍 Cek user berdasarkan NIP
        $user = User::where('nip', $nip)->first();

$user = User::where('nip', $request->nip)->first();

// ❌ jika user tidak ada ATAU password salah
if (!$user || !Hash::check($request->password, $user->password)) {
    return back()->withErrors([
        'login' => 'Wrong account or password'
    ])->withInput();
}


        // 🔑 Login user
        Auth::login($user);

        // 🔄 Regenerate session (WAJIB)
        $request->session()->regenerate();

        // ✅ FIX: redirect ke route yang benar
        return redirect()->route('inspeksi.dashboard');
    }

    /**
     * 🔥 LOGOUT
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // 🔐 Hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}