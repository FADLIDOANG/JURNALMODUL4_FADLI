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
    <h1 class="text-3xl font-semibold text-gray-800 mb-2">Manajemen Artikel</h1>
    <p class="text-gray-700 mb-6">Selamat datang di halaman manajemen artikel! Di sini Anda dapat menambah, mengedit, dan menghapus artikel sesuai kebutuhan.</p>

    <div class="mb-4">
        <a href="{{ route('admin.create') }}" class="bg-blue-600 text-white py-2 px-6 rounded-full shadow hover:bg-blue-700 transition duration-200">
            Tambah Artikel
        </a>
    </div>
</div>

<div class="bg-white p-6 rounded shadow">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($articles as $article)
            <div class="relative bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-200 group">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-28 object-cover rounded mb-4 relative z-10">
                @endif

                <div class="flex justify-between items-center mt-4 relative z-10">
                    <h2 class="text-xl font-semibold text-gray-900 pr-4">
                        {{ $article->title }}
                    </h2>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.edit', $article) }}" class="text-blue-600 hover:underline z-20">Edit</a>
                        <p>|</p>
                        <form action="{{ route('admin.destroy', $article) }}" method="POST" class="inline z-20">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </div>
                </div>

                <p class="text-sm text-gray-700 mt-2 mb-2 text-justify relative z-10">
                    {{ Str::limit($article->content, 100) }}
                </p>
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
