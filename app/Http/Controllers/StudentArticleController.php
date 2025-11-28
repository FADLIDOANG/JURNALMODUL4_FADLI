<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;

class StudentArticleController extends Controller
{
    // List semua artikel milik mahasiswa yang sedang login
    public function index()
    {
        $articles = Article::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mahasiswa.index', compact('articles'));
    }

    // Tampilkan form create
    public function create()
    {
        $article = new Article();
        return view('mahasiswa.create', compact('article'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only('title', 'content', 'image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $data['image'] = $path;
        }

        auth()->user()->articles()->create($data);

        session()->flash('success', 'Artikel berhasil dibuat!');
        return redirect()->route('mahasiswa.index');
    }

    public function edit(Article $article)
    {
        // hanya pemilik boleh mengedit
        if ($article->user_id !== auth()->id()) {
            return redirect()->route('mahasiswa.index')->with('error', 'Anda tidak memiliki izin untuk mengedit artikel ini.');
        }

        return view('mahasiswa.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== auth()->id()) {
            return redirect()->route('mahasiswa.index')->with('error', 'Anda tidak memiliki izin untuk mengubah artikel ini.');
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only('title', 'content', 'image');

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::delete('public/' . $article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        session()->flash('success', 'Artikel berhasil diperbarui!');
        return redirect()->route('mahasiswa.index');
    }

    public function destroy(Article $article)
    {
        if ($article->user_id !== auth()->id()) {
            return redirect()->route('mahasiswa.index')->with('error', 'Anda tidak memiliki izin untuk menghapus artikel ini.');
        }

        if ($article->image) {
            Storage::delete('public/' . $article->image);
        }

        $article->delete();

        session()->flash('success', 'Artikel berhasil dihapus!');
        return redirect()->route('mahasiswa.index');
    }
}
