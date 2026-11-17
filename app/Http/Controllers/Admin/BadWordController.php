<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BadWord;
use Illuminate\Http\Request;

class BadWordController extends Controller
{
    public function index()
    {
        $badWords = BadWord::latest()->get();
        return view('admin.filter_words.index', compact('badWords'));
    }

    public function store(Request $request)
    {
        $word = strtolower(trim($request->input('word', '')));
        $request->merge(['word' => $word]);

        $request->validate([
            'word' => 'required|string|unique:bad_words,word|max:50',
        ], [
            'word.unique' => 'Kata ini sudah ada dalam daftar filter kata kasar!',
            'word.required' => 'Kata tidak boleh kosong!',
        ]);

        BadWord::create([
            'word' => $word,
        ]);

        return back()->with('success', 'Kata terblokir berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $word = BadWord::findOrFail($id);
        $word->delete();

        return back()->with('success', 'Kata terblokir berhasil dihapus!');
    }
}
