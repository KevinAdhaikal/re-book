<?php

namespace App\Services;

use App\Models\BadWord;

class ContentFilterService
{
    /**
     * Daftar kata yang memiliki makna ganda (satwa / literatur cerita vs makian)
     */
    protected static array $ambiguousWords = [
        'anjing',
        'babi',
        'monyet',
        'kampret',
        'buaya',
        'ular',
        'setan',
        'iblis',
        'asu',
        'kunyuk',
        'kirik',
    ];

    /**
     * Sensor kata kasar dari teks input menjadi tanda bintang (****)
     * Menggunakan analisis konteks cerdas untuk membedakan satwa/cerita vs makian.
     *
     * @param string|null $text
     * @return string|null
     */
    public static function filter(?string $text): ?string
    {
        if ($text === null || trim($text) === '') {
            return $text;
        }

        $badWords = BadWord::pluck('word')->filter()->toArray();
        if (empty($badWords)) {
            return $text;
        }

        // Urutkan kata dari yang paling panjang terlebih dahulu untuk mencegah overlapping
        usort($badWords, function ($a, $b) {
            return mb_strlen($b) <=> mb_strlen($a);
        });

        // Pola penanda makian (abusive indicators)
        $abusivePrefixPattern = '/(?:dasar|si|muka|otak|bau|bacot|bego|tolol|mati\s+lu|mati\s+lo|woi|woy)\s*$/iu';
        $abusiveSuffixPattern = '/^\s*(?:lu|lo|loe|kau|kamu|ente|bgt|bet|banget|amat|sialan)\b/iu';

        foreach ($badWords as $word) {
            $word = trim($word);
            if ($word === '') {
                continue;
            }

            $lowerWord = mb_strtolower($word);
            $isAmbiguous = in_array($lowerWord, self::$ambiguousWords);

            $quoted = preg_quote($word, '/');
            $pattern = '/(?<=^|[^a-zA-Z0-9])(' . $quoted . '(?:nya|lah|kan|mu|ku|kah)?)(?=[^a-zA-Z0-9]|$)/iu';

            if (!$isAmbiguous) {
                // Kata kasar murni (non-ambigu) -> selalu disensor tanpa syarat
                $text = preg_replace_callback($pattern, function ($matches) {
                    return str_repeat('*', mb_strlen($matches[1]));
                }, $text);
                continue;
            }

            // Kata ambigu (satwa / fabel / cerita): evaluasi konteks sekitar
            if (!preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE)) {
                continue;
            }

            // Proses dari kanan ke kiri agar offset tetap presisi saat substring diganti
            for ($i = count($matches[0]) - 1; $i >= 0; $i--) {
                $matchedWord = $matches[0][$i][0];
                $offset = $matches[0][$i][1];
                $len = strlen($matchedWord);

                // Cuplikan teks sebelum dan sesudah kata
                $before = substr($text, max(0, $offset - 35), min($offset, 35));
                $after = substr($text, $offset + $len, 35);

                $isAbusive = false;

                // 1. Didahului kata tunjuk makian ("dasar anjing", "si babi")
                if (preg_match($abusivePrefixPattern, $before)) {
                    $isAbusive = true;
                }

                // 2. Diikuti subjek makian / partikel emosi ("anjing lu", "anjing bet")
                if (preg_match($abusiveSuffixPattern, $after)) {
                    $isAbusive = true;
                }

                // 3. Sebagai interjeksi umpatan mandiri di awal kalimat atau setelah tanda seru
                if (($offset === 0 || preg_match('/[.!?]\s*$/', $before)) && preg_match('/^[!,]/', $after)) {
                    $isAbusive = true;
                }

                // Jika terdeteksi sebagai umpatan, sensor dengan tanda bintang
                if ($isAbusive) {
                    $replacement = str_repeat('*', mb_strlen($matchedWord));
                    $text = substr_replace($text, $replacement, $offset, $len);
                }
            }
        }

        return $text;
    }
}
