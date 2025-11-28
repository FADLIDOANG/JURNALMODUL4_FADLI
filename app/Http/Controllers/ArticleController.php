<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    // Ambil semua artikel dari database, urutkan dari yang terbaru, dan kirimkan ke view 'admin.index'.
    public function index()
    {
        // Ambil semua artikel terbaru dulu, sertakan relasi user untuk menampilkan penulis, dan paginate
        $articles = Article::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.index', compact('articles'));
    }

    // Tampilkan form untuk membuat artikel baru (view 'admin.create').
    public function create()
    {
        // Siapkan model kosong agar form blade dapat menggunakan binding yang sama untuk create/edit
        $article = new Article();

        return view('admin.create', compact('article'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $articleData = $request->only('title', 'content', 'image');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
            $articleData['image'] = $imagePath;
        }

        auth()->user()->articles()->create($articleData);

        session()->flash('success', 'Artikel berhasil dibuat!');
        return redirect()->route('admin.index');
    }

    // Tampilkan form edit artikel tertentu (view 'admin.edit') dengan data artikel yang dipilih.
    public function edit(Article $article)
    {
        // Tampilkan form edit dengan data article yang dikirim ke view 'admin.edit'
        return view('admin.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $articleData = $request->only('title', 'content', 'image');

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::delete('public/' . $article->image);
            }

            $imagePath = $request->file('image')->store('articles', 'public');
            $articleData['image'] = $imagePath;
        }

        $article->update($articleData);

        session()->flash('success', 'Artikel berhasil diperbarui!');

        return redirect()->route('admin.index');
    }

    // - Hapus gambar terkait jika ada
    // - Hapus data artikel dari database
    // - Redirect ke route 'admin.index' dengan pesan sukses
    public function destroy(Article $article)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Admin atau pemilik artikel saja yang dapat menghapus
        if ($user->id !== $article->user_id && $user->role !== 'admin') {
            return redirect()->route('admin.index')->with('error', 'Anda tidak memiliki izin untuk menghapus artikel ini.');
        }

        // Hapus file gambar jika ada
        if ($article->image) {
            // mengikuti pattern penyimpanan pada controller lain
            Storage::delete('public/' . $article->image);
        }

        $article->delete();

        session()->flash('success', 'Artikel berhasil dihapus!');
        return redirect()->route('admin.index');
    }

    // - Tampilkan detail artikel tertentu berdasarkan ID
    // - Sertakan relasi komentar dan user pada komentar
    // - Kirim ke view 'articles.show'
    public function show($id)
    {
        // Ambil artikel beserta penulisnya dan komentar beserta user komentar
        $article = Article::with(['user', 'comments.user'])
            ->findOrFail($id);

        return view('articles.show', compact('article'));
    }
}
