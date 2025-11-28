@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
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

    <div class="bg-white p-8 rounded shadow-lg">
        @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-104 object-cover rounded mb-4">
        @endif

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-gray-800">{{ $article->title }}</h1>
            <p class="text-sm text-gray-500 text-justify">{{ $article->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
        </div>
        <p class="mt-4 text-gray-700">{{ $article->content }}</p>
    </div>

    @if(auth()->check() && (auth()->user()->role == 'mahasiswa' || auth()->user()->role == 'admin'))
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-2">Tinggalkan Komentar</h2>
            <form method="POST" action="/articles/{{ $article->id }}/comments">
                @csrf
                <textarea name="comment" rows="3" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                <button class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition duration-300">
                    Kirim
                </button>
            </form>
        </div>
    @endif

    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4">Komentar</h2>
        @forelse($article->comments as $comment)
            <div class="bg-gray-100 p-4 mb-3 rounded-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1">
                        <span class="material-icons text-gray-600 text-2xl">
                            account_circle
                        </span>
                        <p class="text-sm font-semibold text-gray-800">{{ $comment->user->name }}</p>
                    </div>
                    @if(auth()->check() && auth()->id() == $comment->user_id)
                        <form action="{{ route('comment.destroy', $comment) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    @endif
                </div>
                <p class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($comment->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                </p>
                <p class="text-gray-700 text-justify mt-2">{{ $comment->comment }}</p>
            </div>
        @empty
            <p class="text-gray-500">Belum ada komentar.</p>
        @endforelse
    </div>
</div>
@endsection
