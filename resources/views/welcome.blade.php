<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 flex items-center justify-center min-h-screen">

    <main class="bg-white dark:bg-gray-800 p-8 rounded shadow-md w-full max-w-md text-center">
        
        <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">
            Welcome 🚀
        </h1>

        <p class="text-gray-600 dark:text-gray-300 mb-6">
            Selamat datang di aplikasi Laravel kamu
        </p>

        <!-- CEK LOGIN (AMAN) -->
        @if (Route::has('login'))
            <div class="space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-blue-500 hover:underline">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
                        Login
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-blue-500 hover:underline">
                            Register
                        </a>
                    @endif
                @endauth
            </div>
        @endif

    </main>

</body>
</html>