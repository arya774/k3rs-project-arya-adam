<x-guest-layout>
    <div class="w-full max-w-md mx-auto mt-10 bg-white p-6 rounded-2xl shadow-lg">

        <h2 class="text-2xl font-bold text-center mb-6">
            Login K3RS
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


        <!-- 🔥 FORM LOGIN (SUDAH FIX) -->
        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <!-- NIP -->
            <div>
                <x-input-label for="nip" value="NIP" />
                <x-text-input
                    id="nip"
                    class="block mt-1 w-full"
                    type="text"
                    name="nip"
                    :value="old('nip')"
                    placeholder="Masukkan NIP"
                    required
                    autofocus
                />
                <x-input-error :messages="$errors->get('nip')" class="mt-2" />
            </div>

            <!-- PASSWORD -->
            <div class="mt-4">
                <x-input-label for="password" value="Password" />
                <x-text-input
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- REMEMBER ME -->
            <div class="block mt-4">
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    >
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <!-- BUTTON LOGIN -->
            <div class="mt-6">
                <x-primary-button class="w-full justify-center">
                    Login
                </x-primary-button>
            </div>
        </form>

    </div>
</x-guest-layout>
