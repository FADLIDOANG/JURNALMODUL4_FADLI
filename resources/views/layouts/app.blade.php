<!DOCTYPE html>
<html>
<head>
    <title>SeputarTelkom</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/logo.png') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md p-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center space-x-2">
            <img src="/logo.png" alt="Logo" class="w-26 h-8">
            <span class="text-lg font-bold text-gray-800"></span>
        </a>
        <div>
            @auth
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-3">
                        <span class="material-icons text-gray-600 text-4xl">
                            account_circle
                        </span>
                        <div class="text-left leading-tight">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                        <button type="submit"
                                class="bg-red-500 text-white px-6 py-2 rounded-full hover:bg-red-600 transition duration-300">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                @if (Request::is('login'))
                    <a href="/register" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition duration-300">Register</a>
                @else
                    <a href="/login" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition duration-300">Login</a>
                @endif
            @endauth
        </div>
    </nav>
    <div class="container mx-auto p-4">
        @yield('content')
    </div>
</body>
</html>