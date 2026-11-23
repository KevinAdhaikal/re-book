<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BadWord;
use App\Services\ContentFilterService;
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
        $rawInput = $request->input('words', $request->input('word', ''));
        if (empty(trim($rawInput))) {
            return back()->withErrors(['word' => 'Kata tidak boleh kosong!']);
        }

        // Pisahkan kata berdasarkan koma atau baris baru (batch input)
        $tokens = preg_split('/[,\r\n]+/', $rawInput);
        $inserted = 0;
        $skipped = 0;

        foreach ($tokens as $token) {
            $cleaned = strtolower(trim($token));
            if ($cleaned === '' || mb_strlen($cleaned) > 50) {
                continue;
            }

            if (BadWord::where('word', $cleaned)->exists()) {
                $skipped++;

                continue;
            }

            BadWord::create([
                'word' => $cleaned,
            ]);
            $inserted++;
        }

        if ($inserted === 0 && $skipped > 0) {
            return back()->withErrors(['word' => 'Kata-kata yang dimasukkan sudah ada dalam daftar filter!']);
        }

        $message = "{$inserted} kata terblokir baru berhasil ditambahkan!";
        if ($skipped > 0) {
            $message .= " ({$skipped} kata diabaikan karena sudah terdaftar)";
        }

        return back()->with('success', $message);
    }

    public function test(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:2000',
        ]);

        $original = $request->input('text');
        $filtered = ContentFilterService::filter($original);

        return response()->json([
            'original' => $original,
            'filtered' => $filtered,
            'has_censorship' => $original !== $filtered,
        ]);
    }

    public function destroy($id)
    {
        $word = BadWord::findOrFail($id);
        $word->delete();

        return back()->with('success', "Kata '{$word->word}' berhasil dihapus dari filter!");
    }
}
