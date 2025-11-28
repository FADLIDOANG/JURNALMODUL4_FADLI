@extends('layouts.app')

@section('content')
@if(session('error') || session('success'))
    @php
        $type = session('error') ? 'error' : 'success';
        $message = session($type);
        $bgColor = $type === 'error' ? 'bg-red-500' : 'bg-green-500';
        $id = $type . 'Message';
        $closeFunction = 'close' . ucfirst($type) . 'Message';
    @endphp

    <div id="{{ $id }}" class="{{ $bgColor }} text-white p-4 rounded-lg mb-6 relative">
        <span>{{ $message }}</span>
        <button class="absolute right-5 text-white font-bold" onclick="{{ $closeFunction }}()">X</button>
    </div>

    <script>
        function {{ $closeFunction }}() {
            document.getElementById('{{ $id }}').classList.add('hidden');
        }

        setTimeout(function() {
            var el = document.getElementById('{{ $id }}');
            if (el) el.classList.add('hidden');
        }, 5000);
    </script>
@endif

<div class="bg-white p-6 rounded shadow mb-4">
    @auth
        <h1 class="text-2xl font-semibold mb-2">Selamat datang, {{ Auth::user()->name }}!</h1>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif
        
        <p class="text-gray-700 mb-4">Dapatkan update terkini dan berita seputar Telkom University di sini!</p>

        <!-- Tampilkan tombol untuk ke halaman admin.index -->
        @if (Auth::user()->role === 'admin')
            <a href="{{ route('admin.index') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition duration-300">
                Ke Halaman Admin
            </a>
        @endif

        @if (Auth::user()->role === 'mahasiswa')
            <a href="{{ route('mahasiswa.index') }}" class="inline-block bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700 transition duration-300 ml-3">
                Kelola Artikel Saya
            </a>
        @endif
    @else
        <h1 class="text-2xl font-semibold mb-2">Selamat datang!</h1>
        <p class="text-gray-700">Silakan login untuk mendapatkan pengalaman terbaik.</p>
    @endauth
</div>

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Artikel Terbaru</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($articles as $article)
            <div class="block bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-200">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-28 object-cover rounded mb-4">
                @endif
                <h2 class="text-lg text-justify font-semibold text-gray-900">{{ $article->title }}</h2>
                <p class="text-sm text-gray-700 mt-2 text-justify mb-2">{{ Str::limit($article->content, 100) }}</p>
                <a href="{{ route('articles.show', $article->id) }}" class="hover:underline text-blue-600 font-medium">Baca Selengkapnya</a>
            </div>
        @empty
            <div class="col-span-4 text-center text-gray-600 font-medium">
                Tidak Ada Artikel yang Tersedia.
            </div>
        @endforelse
    </div>
</div>
@endsection
