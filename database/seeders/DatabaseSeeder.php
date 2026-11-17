<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use App\Models\BadWord;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::firstOrCreate(
            ['email' => 'admin@rebook.com'],
            [
                'name'     => 'Admin Rebook',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'status'   => 'Aktif',
            ]
        );

        // 2. Akun Member Demo
        User::firstOrCreate(
            ['email' => 'member@rebook.com'],
            [
                'name'     => 'Zivia Valleria',
                'password' => Hash::make('password123'),
                'role'     => 'member',
                'status'   => 'Aktif',
            ]
        );

        // 3. 10 Katalog Buku Lengkap
        $books = [
            [
                'id' => 1,
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'genre' => 'Novel',
                'description' => 'Kisah perjuangan 10 anak di Belitung dalam menuntut ilmu di sekolah yang serba terbatas.',
                'cover' => 'covers/Bi2ouwz5h1a5BOLo3wB8RuwZbBN64GF6wxerEsZI.jpg',
            ],
            [
                'id' => 2,
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'genre' => 'Sejarah',
                'description' => 'Kisah perjalanan Minke di era pergerakan nasional Indonesia.',
                'cover' => 'covers/Z67qFGxxX3hgnAZQIpatYafzk6HEII0r3iqa1E54.jpg',
            ],
            [
                'id' => 3,
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'genre' => 'Self-Dev',
                'description' => 'Perubahan kecil yang memberikan hasil luar biasa untuk membentuk kebiasaan baik.',
                'cover' => 'covers/v5XQcScTfSulhEUAWOOytIFxyeRkxRt1bOBlxYcr.jpg',
            ],
            [
                'id' => 4,
                'title' => 'The Let Them Theory',
                'author' => 'Mel Robbins',
                'genre' => 'Self-Dev',
                'description' => 'Buku yg membahas tentang konsep melepaskan kontrol terhadap orang lain dan fokus pada hal yang bisa kita kendalikan, yaitu diri sendiri.',
                'cover' => 'covers/tZ19KJciyfNkZmKM2uBmvHSeWaAfOxmUsEkijQIu.jpg',
            ],
            [
                'id' => 5,
                'title' => 'Thinking, Fast and Slow',
                'author' => 'Daniel Kahneman',
                'genre' => 'Self-Dev',
                'description' => 'Buku ini mengajak pembaca memahami bagaimana manusia mengambil keputusan dan mengapa kita sering membuat kesalahan berpikir tanpa menyadarinya.',
                'cover' => 'covers/f81CDcRUKDHAoUfn6SIK7BYEySPWpjYZ2LzyiIyI.jpg',
            ],
            [
                'id' => 6,
                'title' => 'The Psychology of Money',
                'author' => 'Morgan Housel',
                'genre' => 'Self-Dev',
                'description' => 'Berbeda dari kebanyakan buku finansial teknis, Morgan Housel membahas hubungan antara perilaku manusia dan uang—kenapa keputusan finansial kita jarang serasional yang kita kira.',
                'cover' => 'covers/T4Zc65xCdWNTmoWwHRjlq1o3fbXmuzO4REG2UioD.jpg',
            ],
            [
                'id' => 7,
                'title' => 'Sapiens',
                'author' => 'Yuval Noah Harari',
                'genre' => 'Sejarah',
                'description' => 'Harari merangkum perjalanan panjang umat manusia, dari masa pemburu-pengumpul hingga masyarakat modern, dengan bahasa yang ringan dan mudah dipahami pembaca awam.',
                'cover' => 'covers/zJlKEc1BXiaflVY1Xa1WNj2fzbpBsKDVgXcbIb8d.jpg',
            ],
            [
                'id' => 8,
                'title' => "Man's Search for Meaning",
                'author' => 'Viktor E. Frankl',
                'genre' => 'Filsafat',
                'description' => 'Buku klasik ini menceritakan pengalaman Viktor Frankl bertahan hidup di kamp konsentrasi Nazi, sekaligus menjelaskan bagaimana manusia tetap bisa menemukan makna di tengah penderitaan paling berat.',
                'cover' => 'covers/xOTreWjY6OpQCEIGrRRF1M3vNBi99Ossh0ef1tdT.jpg',
            ],
            [
                'id' => 9,
                'title' => 'Educated',
                'author' => 'Tara Westover',
                'genre' => 'Non-Fiksi',
                'description' => 'Menceritakan perjuangan Tara Westover memperoleh pendidikan setelah tumbuh dalam keluarga yang menolak sistem sekolah formal.',
                'cover' => 'covers/hBANRdDZkhPEO10Okws3rOwNQ29fS2nqkFHAVKjv.jpg',
            ],
            [
                'id' => 10,
                'title' => 'Make it Happen',
                'author' => 'Prita Hapsari Ghozie',
                'genre' => 'Non-Fiksi',
                'description' => 'Buku ini berisi tahapan-tahapan dalam perencanaan keuangan untuk mewujudkan berbagai mimpi Anda.',
                'cover' => 'covers/7Ayn1WFb0T5jBNf29RKX2eidwgrGhJbKXPHAgp3X.jpg',
            ],
        ];

        foreach ($books as $b) {
            Book::updateOrCreate(['id' => $b['id']], $b);
        }

        // Sample Ulasan Asli
        Review::updateOrCreate(
            ['id' => 1],
            [
                'user_id' => 2,
                'book_id' => 4,
                'rating' => 5,
                'content' => 'Buku ini sangat bagus untuk melatih kendali kontrol kita terhadap diri kita sendiri atas segala hal yang diluar kendali diri kita. Recommended banget untuk kalian yang mau coba untuk memperbaiki dirimu menjadi versi yang lebih baik lagi.',
            ]
        );

        // 4. Kamus Kata Kasar Terblokir Lengkap
        $badWords = [
            'bangsat', 'bajingan', 'keparat', 'brengsek', 'kampret',
            'kontol', 'memek', 'pantek', 'itil', 'peler', 'jembut', 'ngentot',
            'bispak', 'pecun', 'perek', 'lonte', 'jablay', 'pukimak', 'kimak',
            'tai', 'taik', 'bangke', 'bacot', 'kasar', 'toxic',
            'jancuk', 'jancok', 'cok', 'cuk', 'asu', 'kirik', 'dancok',
            'bodoh', 'goblok', 'tolol', 'idiot', 'bego', 'bloon', 'dungu',
            'anjing', 'babi', 'monyet', 'kunyuk', 'buaya', 'ular', 'setan', 'iblis',
        ];
        foreach ($badWords as $w) {
            BadWord::firstOrCreate(['word' => $w]);
        }

        // 5. Data Komunitas Organik (Buku Fiksi/Manga, User Persona, Top 4, Ulasan, Rak, Follow)
        $this->call(OrganicCommunitySeeder::class);
    }
}
