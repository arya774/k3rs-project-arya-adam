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
     * ================= LOGIN =================
     */
    public function login(Request $request)
    {
        // ✅ VALIDASI
        $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        // 🔍 CEK USER
        $user = User::where('nip', $request->nip)->first();

        // 🔥 AUTO REGISTER (OPSIONAL)
        if (!$user) {
            $user = User::create([
                'name' => 'User ' . $request->nip,
                'nip' => $request->nip,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('inspeksi.dashboard')
                ->with('success', 'User baru berhasil dibuat');
        }

        // 🔐 LOGIN (PAKAI ATTEMPT)
        if (Auth::attempt([
            'nip' => $request->nip,
            'password' => $request->password
        ])) {
            $request->session()->regenerate();

            return redirect()->route('inspeksi.dashboard')
                ->with('success', 'Login berhasil');
        }

        // ❌ GAGAL LOGIN
        return back()->withErrors([
            'login' => 'NIP atau password salah'
        ])->withInput();
    }

    /**
     * ================= LOGOUT =================
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Berhasil logout');
    }
}