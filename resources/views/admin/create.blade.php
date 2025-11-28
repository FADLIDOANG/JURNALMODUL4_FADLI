@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Buat Artikel Baru</h1>

    <form method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg">
        @csrf
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-medium mb-2">Judul Artikel</label>
            <input type="text" name="title" id="title" placeholder="Masukkan Judul Artikel" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="content" class="block text-gray-700 text-sm font-medium mb-2">Konten Artikel</label>
            <textarea name="content" id="content" placeholder="Masukkan Konten Artikel" rows="6" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
        </div>

        <div class="mb-4">
            <label for="image" class="block text-gray-700 text-sm font-medium mb-2">Gambar Artikel</label>
            <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

            <div id="imagePreview" class="mt-2 mb-6">
                <p id="imageMessage" class="text-sm text-gray-500">
                    Preview Gambar:<br>
                    <span>Tidak Ada Gambar</span>
                </p>
                <img id="preview" class="w-64 h-auto rounded mt-2 hidden" />
            </div>
        </div>

        <div class="flex justify-start items-center mt-6 p-2 space-x-4">
            <a href="{{ route('admin.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-full hover:bg-gray-400 transition duration-300">
                Kembali
            </a>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700 transition duration-300">
                Simpan
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const message = document.getElementById('imageMessage');
        const preview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            message.textContent = 'Preview Gambar:';
        } else {
            imagePreview.classList.add('hidden');
            preview.src = '';
            message.textContent = '';
        }
    }
</script>
@endsection
