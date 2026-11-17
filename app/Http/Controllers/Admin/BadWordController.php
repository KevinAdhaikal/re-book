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
        $request->validate([
            'word' => 'required|string|unique:bad_words,word|max:50',
        ]);

        BadWord::create([
            'word' => strtolower(trim($request->word)),
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
