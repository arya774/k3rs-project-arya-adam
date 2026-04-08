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

        $user = User::where('nip', $request->nip)->first();

        // 🔥 JIKA USER BELUM ADA → AUTO REGISTER
        if (!$user) {
            $user = User::create([
                'name' => 'User ' . $request->nip,
                'nip' => $request->nip,
                'password' => Hash::make($request->password)
            ]);
        }

        // 🔥 CEK PASSWORD
        if (Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect()->route('inspeksi.dashboard');
        }

        return back()->with('error', 'Password salah');
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}