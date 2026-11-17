<?php

namespace App\Services;

use App\Models\BadWord;

class ContentFilterService
{
    /**
     * Sensor kata kasar dari teks input menjadi tanda bintang (****)
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

        foreach ($badWords as $word) {
            $word = trim($word);
            if ($word === '') {
                continue;
            }

            // Pola regex dengan lookbehind & lookahead:
            // 1. Tidak diawali karakter alfanumerik (mencegah false positive seperti 'panjang' -> 'anjing')
            // 2. Mendukung akhiran umum bahasa Indonesia (-nya, -lah, -kan, -mu, -ku, -kah)
            // 3. Tidak diikuti karakter alfanumerik (kecuali tanda baca atau batas kata)
            $quoted = preg_quote($word, '/');
            $pattern = '/(?<=^|[^a-zA-Z0-9])' . $quoted . '(?:nya|lah|kan|mu|ku|kah)?(?=[^a-zA-Z0-9]|$)/iu';

            $text = preg_replace_callback($pattern, function ($matches) {
                return str_repeat('*', mb_strlen($matches[0]));
            }, $text);
        }

        return $text;
    }
}
