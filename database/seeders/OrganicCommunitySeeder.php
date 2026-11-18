<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Comment;
use App\Models\ReadingShelf;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrganicCommunitySeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------------------------------------------
        // 1. TAMBAH BUKU BARU BERVARIASI (MANGA, FIKSI, SASTRA, NON-FIKSI)
        // -----------------------------------------------------------------
        $newBooksData = [
            [
                'title'       => 'Chainsaw Man (Vol. 1)',
                'author'      => 'Tatsuki Fujimoto',
                'genre'       => 'Manga',
                'description' => 'Denji, seorang pemuda miskin yang terbebani utang mendiang ayahnya, hidup bersama iblis gergaji kecil bernama Pochita. Setelah dikhianati dan dibunuh oleh yakuza yang bersekutu dengan Zombie Devil, Pochita menyatu dengan jantung Denji, melahirkannya kembali sebagai Chainsaw Man. Ia kemudian direkrut oleh Makima ke dalam Divisi 4 Pemburu Iblis Keamanan Publik.',
                'cover'       => 'covers/chainsaw_man.webp',
            ],
            [
                'title'       => 'Goodbye, Eri',
                'author'      => 'Tatsuki Fujimoto',
                'genre'       => 'Manga',
                'description' => 'One-shot sinematik yang memukau tentang Yuta, seorang siswa SMP yang merekam detik-detik terakhir kehidupan ibunya yang sakit atas permintaannya. Film buatannya diejek habis-habisan saat festival sekolah. Putus asa, ia berniat bunuh diri di atap rumah sakit, namun bertemu dengan Eri—gadis misterius yang mengajaknya menonton film bersama dan membuat proyek film baru yang mengaburkan batas antara kenyataan dan fiksi.',
                'cover'       => 'covers/goodbye_eri.jpg',
            ],
            [
                'title'       => 'Look Back',
                'author'      => 'Tatsuki Fujimoto',
                'genre'       => 'Manga',
                'description' => 'Kisah liris tentang Fujino, siswi kelas 4 SD yang percaya diri dengan bakat menggambar manga strip di koran sekolah, dan Kyomoto, siswi agorafobia yang tak pernah masuk kelas namun memiliki kemampuan menggambar latar yang luar biasa. Hubungan persahabatan, persaingan, rasa kehilangan, dan dorongan tak terbendung untuk terus berkarya tersaji begitu emosional.',
                'cover'       => 'covers/look_back.jpg',
            ],
            [
                'title'       => "Frieren: Beyond Journey's End",
                'author'      => 'Kanehito Yamada & Tsukasa Abe',
                'genre'       => 'Manga',
                'description' => 'Kelompok pahlawan telah mengalahkan Raja Iblis dan membawa perdamaian. Bagi elf penyihir bernama Frieren yang hidup ribuan tahun, petualangan 10 tahun itu terasa sekejap mata. Setelah sang pahlawan Himmel meninggal karena usia tua, Frieren merasa menyesal karena tidak pernah benar-benar mengenal manusia. Ia pun memulai perjalanan baru untuk memahami arti ikatan dan kenangan fana.',
                'cover'       => 'covers/frieren.jpg',
            ],
            [
                'title'       => 'Laut Bercerita',
                'author'      => 'Leila S. Chudori',
                'genre'       => 'Fiksi Sejarah',
                'description' => 'Sebuah novel tentang kehilangan, keberanian, dan suara yang ditenggelamkan. Mengisahkan Biru Laut, aktivis mahasiswa era 1998 yang diculik dan disekap di tempat tak dikenal karena memperjuangkan demokrasi. Paruh kedua dinarasikan oleh Asmara Jati, adik Laut, yang bersama para keluarga korban terus mencari kabar dan menolak lupa di seberang Istana.',
                'cover'       => 'covers/laut_bercerita.jpg',
            ],
            [
                'title'       => 'Cantik Itu Luka',
                'author'      => 'Eka Kurniawan',
                'genre'       => 'Sastra',
                'description' => 'Karya sastra realisme magis fenomenal yang berlatar di kota fiktif Halimunda. Suatu petang di akhir pekan, Dewi Ayu bangkit dari kuburnya setelah 21 tahun kematiannya. Melalui kisah Dewi Ayu dan keempat anak perempuannya, novel ini merajut dongeng, tragedi keluarga, kutukan kecantikan, dan sejarah kelam penjajahan hingga pembantaian massal di Indonesia.',
                'cover'       => 'covers/cantik_itu_luka.jpg',
            ],
            [
                'title'       => 'Filosofi Teras',
                'author'      => 'Henry Manampiring',
                'genre'       => 'Filsafat',
                'description' => 'Penerapan praktis filsafat Stoisisme Yunani-Romawi kuno (Stoa) yang dikemas secara santai dan relevan untuk mengatasi kekhawatiran, overthinking, serta emosi negatif generasi masa kini. Membimbing pembaca membedakan hal-hal yang berada di bawah kendali kita dan hal-hal yang di luar kendali kita demi mencapai ketenangan jiwa.',
                'cover'       => 'covers/filosofi_teras.png',
            ],
            [
                'title'       => 'Hujan',
                'author'      => 'Tere Liye',
                'genre'       => 'Fiksi Ilmiah',
                'description' => 'Berlatar tahun 2042 setelah letusan gunung purba dahsyat meluluhlantakkan sebagian besar peradaban manusia. Mengikuti kisah Lail yang kehilangan keluarganya, persahabatannya dengan Esok yang jenius di bidang teknologi, serta dilema tentang kenangan dan cinta: apakah melupakan rasa sakit selalu menjadi pilihan terbaik saat teknologi mampu menghapus ingatan manusia?',
                'cover'       => 'covers/hujan.jpg',
            ],
            [
                'title'       => 'Solo Leveling (Vol. 1)',
                'author'      => 'Chugong',
                'genre'       => 'Fantasi',
                'description' => 'Ketika gerbang misterius menghubungkan dunia manusia dengan monster, segelintir orang terbangun dengan kekuatan gaib yang disebut Hunter. Sung Jin-woo dikenal sebagai "Hunter Terlemah di Dunia". Terjebak dalam Double Dungeon mematikan, Jin-woo menyelesaikan quest rahasia dan menjadi satu-satunya orang yang bisa "Level Up" secara tanpa batas.',
                'cover'       => 'covers/solo_leveling.jpg',
            ],
        ];

        foreach ($newBooksData as $bData) {
            Book::firstOrCreate(['title' => $bData['title']], $bData);
        }

        $allBooks = Book::all();

        // -----------------------------------------------------------------
        // 2. TAMBAH 15 MEMBER PERSONA DENGAN BIO & GENRE REALISTIK
        // -----------------------------------------------------------------
        $membersData = [
            [
                'name'            => 'Dimas Arya Pratama',
                'email'           => 'dimas.arya@rebook.com',
                'avatar'          => 'avatars/member_avatar_1.jpg',
                'bio'             => 'Pecinta manga seinen & dark fantasy. Terobsesi dengan sinematografi panel Tatsuki Fujimoto dan eksplorasi psikologi karakter fiksi.',
                'favorite_genres' => ['Manga', 'Fantasi', 'Fiksi', 'Psychology'],
            ],
            [
                'name'            => 'Nabila Safira',
                'email'           => 'nabila.safira@rebook.com',
                'avatar'          => 'avatars/member_avatar_2.jpg',
                'bio'             => 'Mahasiswi sastra Indonesia. Senang membaca fiksi sejarah, novel realisme magis, dan karya-karya yang bersuara lantang tentang kemanusiaan.',
                'favorite_genres' => ['Sastra', 'Fiksi Sejarah', 'Novel', 'Sejarah'],
            ],
            [
                'name'            => 'Kevin Sanjaya',
                'email'           => 'kevin.tech@rebook.com',
                'avatar'          => 'avatars/member_avatar_3.jpg',
                'bio'             => 'Software engineer di siang hari, pembaca buku stoisisme dan behavioral economics di malam hari. Selalu mencari pola di balik kebiasaan manusia.',
                'favorite_genres' => ['Self-Dev', 'Filsafat', 'Technology', 'Psychology'],
            ],
            [
                'name'            => 'Annisa Putri Rahayu',
                'email'           => 'annisa.putri@rebook.com',
                'avatar'          => 'avatars/member_avatar_4.jpg',
                'bio'             => 'Kolektor novel Tere Liye dan kisah-kisah coming-of-age. Suka secangkir kopi hangat ditemani buku yang menguras air mata.',
                'favorite_genres' => ['Fiksi Ilmiah', 'Novel', 'Romance', 'Fiksi'],
            ],
            [
                'name'            => 'Rian Hidayat',
                'email'           => 'rian.hidayat@rebook.com',
                'avatar'          => 'avatars/member_avatar_5.jpg',
                'bio'             => 'Komikus indie & ilustrator. Meneliti alur paneling, visual storytelling, dan pace narasi manga modern seperti Look Back dan Chainsaw Man.',
                'favorite_genres' => ['Manga', 'Sastra', 'Fiksi', 'Education'],
            ],
            [
                'name'            => 'Sarah Maharani',
                'email'           => 'sarah.maharani@rebook.com',
                'avatar'          => 'avatars/member_avatar_6.jpg',
                'bio'             => 'Pengamat psikologi perkembangan. Gemar menelaah buku non-fiksi memoar dan biografi orang-orang yang bangkit dari trauma.',
                'favorite_genres' => ['Non-Fiksi', 'Psychology', 'Biography', 'Self-Dev'],
            ],
            [
                'name'            => 'Fajar Ramadhan',
                'email'           => 'fajar.ramadhan@rebook.com',
                'avatar'          => 'avatars/member_avatar_7.jpg',
                'bio'             => 'Penggemar novel fantasi tingkat akut dan shonen/seinen manga. Menanti setiap rilis volume manga fisik dengan antusias.',
                'favorite_genres' => ['Manga', 'Fantasi', 'Aksi', 'Fiksi'],
            ],
            [
                'name'            => 'Dinda Kirana',
                'email'           => 'dinda.kirana@rebook.com',
                'avatar'          => 'avatars/member_avatar_8.jpg',
                'bio'             => 'Penikmat cerita slice-of-life, kisah cinta penuh emosi, dan eksplorasi perjalanan hidup. Reviewer santai di media sosial.',
                'favorite_genres' => ['Novel', 'Fiksi Ilmiah', 'Romance', 'Sastra'],
            ],
            [
                'name'            => 'Rendy Oktavian',
                'email'           => 'rendy.oktavian@rebook.com',
                'avatar'          => 'avatars/member_avatar_9.jpg',
                'bio'             => 'Mencoba menerapkan prinsip dikotomi kendali Epiktetos dalam dunia modern. Pembaca setia Morgan Housel dan Yuval Noah Harari.',
                'favorite_genres' => ['Filsafat', 'Self-Dev', 'Sejarah', 'Business'],
            ],
            [
                'name'            => 'Tiara Andini',
                'email'           => 'tiara.andini@rebook.com',
                'avatar'          => 'avatars/member_avatar_10.jpg',
                'bio'             => 'Booktuber & podcaster literasi. Berkelana dari sastra kanonik Indonesia hingga graphic novel kontemporer.',
                'favorite_genres' => ['Manga', 'Sastra', 'Fiksi Sejarah', 'Novel'],
            ],
            [
                'name'            => 'Gilang Permana',
                'email'           => 'gilang.permana@rebook.com',
                'avatar'          => 'avatars/member_avatar_11.jpg',
                'bio'             => 'Pecinta kisah penuh teka-teki, thriller psikologis, dan drama supernatural. Suka twist ending yang tidak tertebak.',
                'favorite_genres' => ['Manga', 'Thriller', 'Mystery', 'Psychology'],
            ],
            [
                'name'            => 'Maya Anggraini',
                'email'           => 'maya.anggraini@rebook.com',
                'avatar'          => 'avatars/member_avatar_12.jpg',
                'bio'             => 'Guru Bahasa dan Sastra. Percaya bahwa membaca fiksi adalah cara terbaik melatih empati dan memahami jiwa manusia.',
                'favorite_genres' => ['Sastra', 'Education', 'Fiksi Sejarah', 'Novel'],
            ],
            [
                'name'            => 'Bagas Wicaksono',
                'email'           => 'bagas.wicaksono@rebook.com',
                'avatar'          => 'avatars/member_avatar_13.jpg',
                'bio'             => 'Hunter rank S di kamar tidur. Penikmat alur zero-to-hero, world building megah, dan pertarungan taktis dalam novel aksi.',
                'favorite_genres' => ['Fantasi', 'Manga', 'Aksi', 'Fiksi'],
            ],
            [
                'name'            => 'Clarissa Devi',
                'email'           => 'clarissa.devi@rebook.com',
                'avatar'          => 'avatars/member_avatar_14.jpg',
                'bio'             => 'Editor naskah penerbitan. Menghabiskan hari dengan menelisik diksi, ritme kalimat, dan resonansi emosional dalam tulisan.',
                'favorite_genres' => ['Sastra', 'Fiksi', 'Non-Fiksi', 'Biography'],
            ],
            [
                'name'            => 'Ilham Saputra',
                'email'           => 'ilham.saputra@rebook.com',
                'avatar'          => 'avatars/member_avatar_15.jpg',
                'bio'             => 'Penikmat sejarah nusantara dan dinamika peradaban manusia. Sering merenung berjam-jam setelah menyelesaikan karya Pram dan Harari.',
                'favorite_genres' => ['Sejarah', 'Fiksi Sejarah', 'Filsafat', 'Non-Fiksi'],
            ],
        ];

        $createdUsers = [];
        foreach ($membersData as $mData) {
            $user = User::updateOrCreate(
                ['email' => $mData['email']],
                [
                    'name'            => $mData['name'],
                    'password'        => Hash::make('password123'),
                    'role'            => 'member',
                    'status'          => 'Aktif',
                    'avatar'          => $mData['avatar'],
                    'bio'             => $mData['bio'],
                    'favorite_genres' => $mData['favorite_genres'],
                ]
            );
            $createdUsers[] = $user;
        }

        // Ambil semua member (termasuk yang lama)
        $allMembers = User::where('role', 'member')->get();

        // -----------------------------------------------------------------
        // 3. SETEL PAJANGAN 4 BUKU FAVORIT UNTUK SEMUA USER SECARA ORGANIK
        // -----------------------------------------------------------------
        $bookMap = $allBooks->keyBy('title');

        $userTop4Mapping = [
            'dimas.arya@rebook.com' => ['Chainsaw Man (Vol. 1)', 'Goodbye, Eri', 'Look Back', 'Solo Leveling (Vol. 1)'],
            'nabila.safira@rebook.com' => ['Laut Bercerita', 'Bumi Manusia', 'Cantik Itu Luka', 'Sapiens'],
            'kevin.tech@rebook.com' => ['Atomic Habits', 'Filosofi Teras', 'The Psychology of Money', 'Thinking, Fast and Slow'],
            'annisa.putri@rebook.com' => ['Hujan', 'Laskar Pelangi', 'Look Back', 'Laut Bercerita'],
            'rian.hidayat@rebook.com' => ['Look Back', 'Goodbye, Eri', 'Chainsaw Man (Vol. 1)', "Frieren: Beyond Journey's End"],
            'sarah.maharani@rebook.com' => ['Educated', "Man's Search for Meaning", 'Atomic Habits', 'Thinking, Fast and Slow'],
            'fajar.ramadhan@rebook.com' => ['Solo Leveling (Vol. 1)', 'Chainsaw Man (Vol. 1)', "Frieren: Beyond Journey's End", 'Look Back'],
            'dinda.kirana@rebook.com' => ['Hujan', 'Laut Bercerita', 'Goodbye, Eri', 'Laskar Pelangi'],
            'rendy.oktavian@rebook.com' => ['Filosofi Teras', 'The Psychology of Money', 'Sapiens', 'Atomic Habits'],
            'tiara.andini@rebook.com' => ['Goodbye, Eri', 'Laut Bercerita', 'Cantik Itu Luka', 'Look Back'],
            'gilang.permana@rebook.com' => ['Chainsaw Man (Vol. 1)', 'Goodbye, Eri', "Man's Search for Meaning", 'Cantik Itu Luka'],
            'maya.anggraini@rebook.com' => ['Bumi Manusia', 'Laut Bercerita', 'Cantik Itu Luka', 'Laskar Pelangi'],
            'bagas.wicaksono@rebook.com' => ['Solo Leveling (Vol. 1)', 'Chainsaw Man (Vol. 1)', "Frieren: Beyond Journey's End", 'Hujan'],
            'clarissa.devi@rebook.com' => ['Cantik Itu Luka', 'Laut Bercerita', 'Look Back', 'Bumi Manusia'],
            'ilham.saputra@rebook.com' => ['Bumi Manusia', 'Sapiens', 'Laut Bercerita', 'Filosofi Teras'],
            // Member-member lama
            'monikacaroline13@gmail.com' => ['Laut Bercerita', 'Atomic Habits', 'Goodbye, Eri', 'Bumi Manusia'],
            'member@rebook.com' => ['Chainsaw Man (Vol. 1)', 'Filosofi Teras', 'Hujan', 'The Psychology of Money'],
            'profile_member1@rebook.com' => ['Bumi Manusia', 'Laskar Pelangi', 'Laut Bercerita', 'Atomic Habits'],
            'profile_member2@rebook.com' => ['Goodbye, Eri', 'Look Back', 'Cantik Itu Luka', "Frieren: Beyond Journey's End"],
        ];

        foreach ($allMembers as $m) {
            $topTitles = $userTop4Mapping[$m->email] ?? null;
            if (!$topTitles) {
                // Fallback 4 buku acak jika belum dipetakan
                $favIds = $allBooks->random(min(4, $allBooks->count()))->pluck('id')->toArray();
            } else {
                $favIds = [];
                foreach ($topTitles as $title) {
                    if (isset($bookMap[$title])) {
                        $favIds[] = $bookMap[$title]->id;
                    }
                }
            }
            $m->favoriteBooks()->sync($favIds);
        }

        // -----------------------------------------------------------------
        // 4. SETEL STATUS RAK BACAAN (WANT TO READ, CURRENTLY READING, READ)
        // -----------------------------------------------------------------
        foreach ($allMembers as $m) {
            $sampleBooks = $allBooks->random(min(6, $allBooks->count()));
            $statuses = [
                ReadingShelf::STATUS_READ,
                ReadingShelf::STATUS_READ,
                ReadingShelf::STATUS_CURRENTLY_READING,
                ReadingShelf::STATUS_WANT_TO_READ,
                ReadingShelf::STATUS_WANT_TO_READ,
            ];

            foreach ($sampleBooks as $idx => $b) {
                $st = $statuses[$idx % count($statuses)];
                ReadingShelf::updateOrCreate(
                    ['user_id' => $m->id, 'book_id' => $b->id],
                    ['status' => $st]
                );
            }
        }

        // -----------------------------------------------------------------
        // 5. SETEL BUKU DISUKAI (BOOK LIKES)
        // -----------------------------------------------------------------
        foreach ($allMembers as $m) {
            $likeBooks = $allBooks->random(rand(4, 8))->pluck('id')->toArray();
            $m->likedBooks()->syncWithoutDetaching($likeBooks);
        }

        // -----------------------------------------------------------------
        // 6. TAMBAH ULASAN ORGANIK DENGAN RATING BERVARIASI & MENDALAM
        // -----------------------------------------------------------------
        $organicReviews = [
            // Chainsaw Man
            [
                'book'    => 'Chainsaw Man (Vol. 1)',
                'email'   => 'dimas.arya@rebook.com',
                'rating'  => 5,
                'content' => 'Tatsuki Fujimoto benar-benar gila! Pacingnya sangat cepat, tanpa basa-basi klise shonen. Denji bukan tipikal protagonis suci yang ingin menyelamatkan dunia, tujuannya sederhana dan manusiawi banget (sarapan roti pakai selai). Visual iblisnya grotesk dan penuh gaya.',
            ],
            [
                'book'    => 'Chainsaw Man (Vol. 1)',
                'email'   => 'rian.hidayat@rebook.com',
                'rating'  => 5,
                'content' => 'Sebagai ilustrator, saya kagum luar biasa dengan paneling Fujimoto. Alur aksinya terbaca seperti gerakan kamera bioskop. Karakter Makima memberi aura mencekam sejak kemunculan pertamanya.',
            ],
            [
                'book'    => 'Chainsaw Man (Vol. 1)',
                'email'   => 'gilang.permana@rebook.com',
                'rating'  => 4,
                'content' => 'Berdarah-darah, liar, tapi anehnya punya hati di hubungan Denji dan Pochita. Ceritanya absurd tapi bikin ketagihan buat langsung baca volume lanjutannya.',
            ],
            [
                'book'    => 'Chainsaw Man (Vol. 1)',
                'email'   => 'maya.anggraini@rebook.com',
                'rating'  => 3,
                'content' => 'Bagi pembaca umum mungkin terasa terlalu vulgar dan penuh kekerasan di beberapa adegan. Tapi harus diakui penokohan Denji adalah dekonstruksi menarik dari kiasan pahlawan shonen.',
            ],

            // Goodbye, Eri
            [
                'book'    => 'Goodbye, Eri',
                'email'   => 'tiara.andini@rebook.com',
                'rating'  => 5,
                'content' => 'Masterpiece one-shot! Cara Fujimoto membagi halaman menjadi panel 4-grid statis seperti bingkai rekaman smartphone benar-benar brilian. Twist fantasi di endingnya membuat saya terdiam 10 menit setelah membaca. Sebuah surat cinta untuk perfilman.',
            ],
            [
                'book'    => 'Goodbye, Eri',
                'email'   => 'dimas.arya@rebook.com',
                'rating'  => 5,
                'content' => 'Karya terbaik Fujimoto sejauh ini melebihi CSM bagi saya. Hubungan antara Yuta dan Eri mempermainkan persepsi pembaca: mana yang dokumenter jujur, mana yang rekayasa kamera. Ending ledakannya sangat ikonik.',
            ],
            [
                'book'    => 'Goodbye, Eri',
                'email'   => 'dinda.kirana@rebook.com',
                'rating'  => 4,
                'content' => 'Sangat menguras emosi dan sedikit membingungkan pada awalnya, tapi setelah membaca ulang bagian akhirnya, semua potongan puzzle terasa pas dan puitis.',
            ],

            // Look Back
            [
                'book'    => 'Look Back',
                'email'   => 'rian.hidayat@rebook.com',
                'rating'  => 5,
                'content' => 'Adegan Fujino berjalan menari di tengah hujan setelah dipuji Kyomoto adalah salah satu momen paling murni dan indah dalam sejarah manga. Cerita ini begitu personal bagi siapa pun yang pernah bergelut dengan karya seni.',
            ],
            [
                'book'    => 'Look Back',
                'email'   => 'annisa.putri@rebook.com',
                'rating'  => 5,
                'content' => 'Air mata saya tumpah tanpa terasa. Tragedi yang diangkat begitu menusuk, tapi endingnya mengingatkan kita mengapa kita harus terus melangkah dan melihat ke belakang dengan penuh rasa syukur.',
            ],
            [
                'book'    => 'Look Back',
                'email'   => 'fajar.ramadhan@rebook.com',
                'rating'  => 4,
                'content' => 'Sederhana, padat, dan tidak ada satu panel pun yang terbuang sia-sia. Fujimoto membuktikan bahwa dia bukan cuma jago bikin komedi gore, tapi juga drama kemanusiaan tingkat tinggi.',
            ],

            // Frieren
            [
                'book'    => "Frieren: Beyond Journey's End",
                'email'   => 'fajar.ramadhan@rebook.com',
                'rating'  => 5,
                'content' => 'Pendekatan fantasi yang sangat tenang dan mendalam. Fokusnya bukan pada mengalahkan musuh, melainkan perjalanan melankolis mengenang teman-teman yang telah tiada. World-building nya halus dan menenangkan.',
            ],
            [
                'book'    => "Frieren: Beyond Journey's End",
                'email'   => 'bagas.wicaksono@rebook.com',
                'rating'  => 4,
                'content' => 'Pertarungan sihirnya taktis tanpa banyak teriakan nama jurus. Hubungan Frieren dan Fern sangat manis, seperti guru dan murid sekaligus ibu dan anak.',
            ],

            // Laut Bercerita
            [
                'book'    => 'Laut Bercerita',
                'email'   => 'nabila.safira@rebook.com',
                'rating'  => 5,
                'content' => 'Membaca Laut Bercerita seperti dicekik pelan-pelan oleh rasa duka yang tak berkesudahan. Diksi Leila S. Chudori sangat puitis namun menusuk tajam. Bagian narasi Asmara Jati di Aksi Kamisan membuat dada terasa sesak. Wajib dibaca oleh generasi muda!',
            ],
            [
                'book'    => 'Laut Bercerita',
                'email'   => 'maya.anggraini@rebook.com',
                'rating'  => 5,
                'content' => 'Sebuah monumen sastra untuk para korban penghilangan paksa 1998. Buku ini mengembalikan martabat dan nama-nama yang berusaha dihapus oleh sejarah penguasa.',
            ],
            [
                'book'    => 'Laut Bercerita',
                'email'   => 'ilham.saputra@rebook.com',
                'rating'  => 5,
                'content' => 'Riset sejarahnya luar biasa detail. Persahabatan di Rumah Klender, aroma tenggiri goreng di Blangguan, dan dinginnya dasar laut yang sunyi. Sangat menyayat hati.',
            ],
            [
                'book'    => 'Laut Bercerita',
                'email'   => 'kevin.tech@rebook.com',
                'rating'  => 4,
                'content' => 'Buku yang membuka mata saya tentang harga mahal demokrasi yang kita nikmati saat ini. Bagian awal sedikit lambat, tetapi begitu masuk bab penyekapan, sulit untuk berhenti membaca.',
            ],

            // Cantik Itu Luka
            [
                'book'    => 'Cantik Itu Luka',
                'email'   => 'clarissa.devi@rebook.com',
                'rating'  => 5,
                'content' => 'Opening line novel ini adalah salah satu yang terhebat dalam sastra Indonesia modern. Eka Kurniawan memadukan kegilaan ala Gabriel Garcia Marquez dengan folklor dan trauma sejarah nusantara. Liar, berani, dan tak terlupakan.',
            ],
            [
                'book'    => 'Cantik Itu Luka',
                'email'   => 'nabila.safira@rebook.com',
                'rating'  => 4,
                'content' => 'Dunia Halimunda sangat memikat. Karakter Dewi Ayu sangat kompleks dan tegar di tengah takdirnya yang tragis. Beberapa bagian memang sangat vulgar dan gelap, jadi perhatikan trigger warning.',
            ],

            // Filosofi Teras
            [
                'book'    => 'Filosofi Teras',
                'email'   => 'rendy.oktavian@rebook.com',
                'rating'  => 5,
                'content' => 'Buku pengubah hidup! Konsep Dikotomi Kendali berhasil menyembuhkan kecemasan saya terhadap masa depan dan karier. Penjelasan Om Piring sangat membumi dengan contoh kasus khas warga Jakarta.',
            ],
            [
                'book'    => 'Filosofi Teras',
                'email'   => 'kevin.tech@rebook.com',
                'rating'  => 4,
                'content' => 'Buku pengantar Stoicism terbaik dalam bahasa Indonesia. Ilustrasi dan diagramnya sangat membantu pemahaman. Sangat direkomendasikan untuk siapa pun yang sering overthinking.',
            ],
            [
                'book'    => 'Filosofi Teras',
                'email'   => 'sarah.maharani@rebook.com',
                'rating'  => 4,
                'content' => 'Banyak keselarasan antara ajaran Stoa di buku ini dengan Cognitive Behavioral Therapy (CBT). Bahasa komunikatif membuatnya mudah dicerna orang awam.',
            ],

            // Hujan
            [
                'book'    => 'Hujan',
                'email'   => 'annisa.putri@rebook.com',
                'rating'  => 5,
                'content' => 'Kisah Lail dan Esok selalu berhasil membuat hati bergetar. Tere Liye memadukan premis sci-fi tentang masa depan dengan kisah cinta yang sederhana dan tulus. Bagian akhirnya menguras air mata!',
            ],
            [
                'book'    => 'Hujan',
                'email'   => 'dinda.kirana@rebook.com',
                'rating'  => 4,
                'content' => 'Buku yang sangat manis sekaligus pilu. Pesan moralnya kuat: bukan melupakan yang membuat kita damai, tapi menerima takdir dengan lapang dada.',
            ],
            [
                'book'    => 'Hujan',
                'email'   => 'dimas.arya@rebook.com',
                'rating'  => 3,
                'content' => 'Premis sci-fi nya cukup menarik untuk novel remaja Indonesia, meskipun di beberapa bagian penyelesaian konfliknya terasa sedikit dramatis.',
            ],

            // Solo Leveling
            [
                'book'    => 'Solo Leveling (Vol. 1)',
                'email'   => 'bagas.wicaksono@rebook.com',
                'rating'  => 5,
                'content' => 'Sensasi kepuasan membaca saat Sung Jin-woo mulai membantai monster dari hunter terlemah jadi overpowered tidak ada tandingannya. Sangat adiktif!',
            ],
            [
                'book'    => 'Solo Leveling (Vol. 1)',
                'email'   => 'fajar.ramadhan@rebook.com',
                'rating'  => 5,
                'content' => 'Aksinya cepat, ilustrasi covernya gahar, dan pacing dungeon-nya bikin tegang terus. Cocok banget buat yang butuh hiburan beroktan tinggi.',
            ],

            // Atomic Habits
            [
                'book'    => 'Atomic Habits',
                'email'   => 'kevin.tech@rebook.com',
                'rating'  => 5,
                'content' => 'Konsep 1% lebih baik setiap hari dan penataan lingkungan (cue) bekerja nyata untuk saya. Buku non-fiksi paling praktis yang pernah saya terapkan dalam kehidupan kerja.',
            ],
            [
                'book'    => 'Atomic Habits',
                'email'   => 'rendy.oktavian@rebook.com',
                'rating'  => 5,
                'content' => 'Buku yang mengubah cara pandang saya dari mengejar target menjadi membangun identitas. Bukan sekadar motivasi kosong, tapi sistem sains perilaku.',
            ],

            // Bumi Manusia
            [
                'book'    => 'Bumi Manusia',
                'email'   => 'ilham.saputra@rebook.com',
                'rating'  => 5,
                'content' => 'Karya agung sastra dunia dari penjara Pulau Buru. Karakter Nyai Ontosoroh adalah lambang perlawanan dan kecerdasan pribumi yang tak tertandingi. Setiap orang Indonesia wajib membaca tetralogi ini.',
            ],
            [
                'book'    => 'Bumi Manusia',
                'email'   => 'maya.anggraini@rebook.com',
                'rating'  => 5,
                'content' => 'Bahasa Pramoedya Ananta Toer begitu bertenaga dan membakar semangat. Kisah Minke dan Annelies adalah cerminan hukum kolonial yang timpang dan melukai kemanusiaan.',
            ],

            // Sapiens
            [
                'book'    => 'Sapiens',
                'email'   => 'ilham.saputra@rebook.com',
                'rating'  => 5,
                'content' => 'Cara Harari menjelaskan bahwa kemampuan menciptakan fiksi/mitos bersama (uang, negara, agama) adalah kunci dominasi Homo sapiens di bumi benar-benar membalikkan logika sejarah konvensional.',
            ],

            // Educated
            [
                'book'    => 'Educated',
                'email'   => 'sarah.maharani@rebook.com',
                'rating'  => 5,
                'content' => 'Memoar Tara Westover sangat mencekam sekaligus menginspirasi. Bagaimana pendidikan formal mampu membebaskan seseorang dari kungkungan fanatisme keluarga yang mengisolasi.',
            ],

            // The Psychology of Money
            [
                'book'    => 'The Psychology of Money',
                'email'   => 'rendy.oktavian@rebook.com',
                'rating'  => 5,
                'content' => 'Buku finansial yang tidak membosankan dengan angka rumit. Morgan Housel mengajarkan bahwa kesuksesan finansial lebih berkaitan dengan perilaku psikologis daripada kepintaran matematika.',
            ],
        ];

        $userMap = $allMembers->keyBy('email');
        $createdReviews = [];

        foreach ($organicReviews as $revData) {
            $book = $bookMap[$revData['book']] ?? null;
            $user = $userMap[$revData['email']] ?? null;

            if ($book && $user) {
                $review = Review::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'book_id' => $book->id,
                    ],
                    [
                        'rating'  => $revData['rating'],
                        'content' => $revData['content'],
                    ]
                );
                $createdReviews[] = $review;
            }
        }

        // -----------------------------------------------------------------
        // 7. TAMBAH LIKES / REAKSI MEMBANTU PADA ULASAN
        // -----------------------------------------------------------------
        foreach ($createdReviews as $rev) {
            // Beri 2 - 8 likes dari member lain secara acak
            $likerCount = rand(2, min(8, count($allMembers)));
            $likers = $allMembers->where('id', '!=', $rev->user_id)->random($likerCount);

            foreach ($likers as $liker) {
                DB::table('review_likes')->updateOrInsert(
                    ['user_id' => $liker->id, 'review_id' => $rev->id],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        // -----------------------------------------------------------------
        // 8. TAMBAH KOMENTAR DISKUSI ANTAR MEMBER PADA ULASAN
        // -----------------------------------------------------------------
        $organicComments = [
            'dimas.arya@rebook.com'   => 'Setuju banget sama poin tentang pacingnya! Bener-bener gak kerasa udah tamat satu volume.',
            'rian.hidayat@rebook.com'  => 'Betul, panelingnya sinematik banget. Saya paling kagum pas transisi adegan aksinya.',
            'nabila.safira@rebook.com' => 'Ulasan yang sangat menyentuh. Memang bagian itu bikin nangis sesenggukan pas baca.',
            'tiara.andini@rebook.com'  => 'Endingnya bener-bener plot twist gila sih, wajib dibaca ulang biar ngeh petunjuk-petunjuk kecilnya!',
            'kevin.tech@rebook.com'    => 'Saya terapkan tips di buku ini dan beneran kerasa perubahannya dalam produktivitas harian.',
            'annisa.putri@rebook.com'  => 'Terima kasih atas ulasannya! Jadi makin mantap buat beli buku fisiknya akhir pekan ini.',
            'fajar.ramadhan@rebook.com'=> 'Karakter utamanya emang badass banget, ga sabar nunggu adaptasi animasinya rilis!',
            'maya.anggraini@rebook.com'=> 'Analisis yang mendalam, senang melihat pembaca muda mengapresiasi karya sastra berbobot seperti ini.',
        ];

        foreach (array_slice($createdReviews, 0, 15) as $idx => $rev) {
            // Pilih 1 - 2 komentator yang bukan pembuat review
            $potentialCommenters = $allMembers->where('id', '!=', $rev->user_id)->random(rand(1, 2));

            foreach ($potentialCommenters as $commenter) {
                $text = $organicComments[$commenter->email] ?? 'Ulasan yang sangat menarik dan membuka sudut pandang baru, terima kasih rekomendasinya!';
                Comment::create([
                    'user_id'   => $commenter->id,
                    'review_id' => $rev->id,
                    'comment'   => $text,
                ]);
            }
        }

        // -----------------------------------------------------------------
        // 9. JARINGAN FOLLOW (PERTEMANAN ANTAR MEMBER)
        // -----------------------------------------------------------------
        foreach ($allMembers as $m) {
            // Follow 3 - 6 member lain
            $followCount = rand(3, min(6, count($allMembers) - 1));
            $toFollow = $allMembers->where('id', '!=', $m->id)->random($followCount)->pluck('id')->toArray();
            $m->following()->syncWithoutDetaching($toFollow);
        }

        $this->command->info('Organic Community Seeder berhasil dijalankan!');
        $this->command->info('- Total Buku: ' . Book::count());
        $this->command->info('- Total Member: ' . User::where('role', 'member')->count());
        $this->command->info('- Total Ulasan: ' . Review::count());
        $this->command->info('- Total Suka Ulasan: ' . DB::table('review_likes')->count());
        $this->command->info('- Total Komentar: ' . Comment::count());
        $this->command->info('- Total Rak Bacaan: ' . ReadingShelf::count());
        $this->command->info('- Total Follow: ' . DB::table('user_follows')->count());
    }
}
