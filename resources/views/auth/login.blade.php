<x-guest-layout>
    <div class="w-full max-w-md mx-auto mt-10 bg-white p-6 rounded-2xl shadow-lg">

        <h2 class="text-2xl font-bold text-center mb-6">

        </h2>


        <!-- SESSION STATUS -->
        <x-auth-session-status
            class="mb-4 text-center text-green-600"
            :status="session('status')"
        />

        <!-- ERROR GLOBAL -->
        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <h1 style="color:red;"></h1>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="login-container">
    <h2>Login K3RS</h2>

    <form method="POST" action="{{ route('login.process') }}">
        @csrf

        <div class="input-group">
            <label>NIP</label>
            <input type="text" name="nip" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="remember">
            <input type="checkbox" name="remember">
            <span>Remember me</span>
        </div>

        <button type="submit" class="btn-login">Login</button>
    </form>
</div>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <!-- 🔥 FORM LOGIN (SUDAH FIX) -->
        <form method="POST" action="{{ route('login.process') }}">
            @csrf
            </div>
</x-guest-layout>
