<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Comment;

class CommentController extends Controller
{
     /**
     * - Validasi input komentar ('comment' wajib diisi dan maksimal 1000 karakter)
     * - Simpan komentar baru pada artikel tertentu, relasikan dengan user yang sedang login
     * - Tampilkan pesan sukses dan redirect ke halaman detail artikel
     */
    public function store(Request $request, Article $article)
    {
        // Validasi input komentar
        $data = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Buat dan simpan komentar terkait article dan user yang sedang login
        $comment = new Comment([
            'comment' => $data['comment'],
            'user_id' => auth()->id(),
        ]);

        $article->comments()->save($comment);

        session()->flash('success', 'Komentar berhasil ditambahkan!');
        return redirect()->route('articles.show', ['article' => $article->id]);
    }

    public function destroy(Comment $comment)
    {
        $user = auth()->user();

        // Hanya pemilik komentar atau admin yang boleh menghapus
        if (!$user) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->id !== $comment->user_id && $user->role !== 'admin') {
            return redirect()->route('articles.show', ['article' => $comment->article_id])
                ->with('error', 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        $articleId = $comment->article_id;
        $comment->delete();

        session()->flash('success', 'Komentar berhasil dihapus!');
        return redirect()->route('articles.show', ['article' => $articleId]);
    }
}
