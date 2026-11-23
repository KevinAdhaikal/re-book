<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Comment;
use App\Models\ReadingShelf;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrganicCommunitySeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------------------------------------------
        // 1. TAMBAH KATALOG BUKU LENGKAP & BERVARIASI (MANGA, FIKSI, SASTRA, NON-FIKSI)
        // -----------------------------------------------------------------
        $newBooksData = [
            [
                'title' => 'Chainsaw Man (Vol. 1)',
                'author' => 'Tatsuki Fujimoto',
                'genre' => 'Manga',
                'description' => 'Denji, seorang pemuda miskin yang terbebani utang mendiang ayahnya, hidup bersama iblis gergaji kecil bernama Pochita. Setelah dikhianati dan dibunuh oleh yakuza yang bersekutu dengan Zombie Devil, Pochita menyatu dengan jantung Denji, melahirkannya kembali sebagai Chainsaw Man. Ia kemudian direkrut oleh Makima ke dalam Divisi 4 Pemburu Iblis Keamanan Publik.',
                'cover' => 'covers/chainsaw_man.webp',
            ],
            [
                'title' => 'Goodbye, Eri',
                'author' => 'Tatsuki Fujimoto',
                'genre' => 'Manga',
                'description' => 'One-shot sinematik yang memukau tentang Yuta, seorang siswa SMP yang merekam detik-detik terakhir kehidupan ibunya yang sakit atas permintaannya. Film buatannya diejek habis-habisan saat festival sekolah. Putus asa, ia berniat bunuh diri di atap rumah sakit, namun bertemu dengan Eri—gadis misterius yang mengajaknya menonton film bersama dan membuat proyek film baru yang mengaburkan batas antara kenyataan dan fiksi.',
                'cover' => 'covers/goodbye_eri.jpg',
            ],
            [
                'title' => 'Look Back',
                'author' => 'Tatsuki Fujimoto',
                'genre' => 'Manga',
                'description' => 'Kisah liris tentang Fujino, siswi kelas 4 SD yang percaya diri dengan bakat menggambar manga strip di koran sekolah, dan Kyomoto, siswi agorafobia yang tak pernah masuk kelas namun memiliki kemampuan menggambar latar yang luar biasa. Hubungan persahabatan, persaingan, rasa kehilangan, dan dorongan tak terbendung untuk terus berkarya tersaji begitu emosional.',
                'cover' => 'covers/look_back.jpg',
            ],
            [
                'title' => "Frieren: Beyond Journey's End",
                'author' => 'Kanehito Yamada & Tsukasa Abe',
                'genre' => 'Manga',
                'description' => 'Kelompok pahlawan telah mengalahkan Raja Iblis dan membawa perdamaian. Bagi elf penyihir bernama Frieren yang hidup ribuan tahun, petualangan 10 tahun itu terasa sekejap mata. Setelah sang pahlawan Himmel meninggal karena usia tua, Frieren merasa menyesal karena tidak pernah benar-benar mengenal manusia. Ia pun memulai perjalanan baru untuk memahami arti ikatan dan kenangan fana.',
                'cover' => 'covers/frieren.jpg',
            ],
            [
                'title' => 'Laut Bercerita',
                'author' => 'Leila S. Chudori',
                'genre' => 'Fiksi Sejarah',
                'description' => 'Sebuah novel tentang kehilangan, keberanian, dan suara yang ditenggelamkan. Mengisahkan Biru Laut, aktivis mahasiswa era 1998 yang diculik dan disekap di tempat tak dikenal karena memperjuangkan demokrasi. Paruh kedua dinarasikan oleh Asmara Jati, adik Laut, yang bersama para keluarga korban terus mencari kabar dan menolak lupa di seberang Istana.',
                'cover' => 'covers/laut_bercerita.jpg',
            ],
            [
                'title' => 'Cantik Itu Luka',
                'author' => 'Eka Kurniawan',
                'genre' => 'Sastra',
                'description' => 'Karya sastra realisme magis fenomenal yang berlatar di kota fiktif Halimunda. Suatu petang di akhir pekan, Dewi Ayu bangkit dari kuburnya setelah 21 tahun kematiannya. Melalui kisah Dewi Ayu dan keempat anak perempuannya, novel ini merajut dongeng, tragedi keluarga, kutukan kecantikan, dan sejarah kelam penjajahan hingga pembantaian massal di Indonesia.',
                'cover' => 'covers/cantik_itu_luka.jpg',
            ],
            [
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'genre' => 'Filsafat',
                'description' => 'Penerapan praktis filsafat Stoisisme Yunani-Romawi kuno (Stoa) yang dikemas secara santai dan relevan untuk mengatasi kekhawatiran, overthinking, serta emosi negatif generasi masa kini. Membimbing pembaca membedakan hal-hal yang berada di bawah kendali kita dan hal-hal yang di luar kendali kita demi mencapai ketenangan jiwa.',
                'cover' => 'covers/filosofi_teras.png',
            ],
            [
                'title' => 'Hujan',
                'author' => 'Tere Liye',
                'genre' => 'Fiksi Ilmiah',
                'description' => 'Berlatar tahun 2042 setelah letusan gunung purba dahsyat meluluhlantakkan sebagian besar peradaban manusia. Mengikuti kisah Lail yang kehilangan keluarganya, persahabatannya dengan Esok yang jenius di bidang teknologi, serta dilema tentang kenangan dan cinta: apakah melupakan rasa sakit selalu menjadi pilihan terbaik saat teknologi mampu menghapus ingatan manusia?',
                'cover' => 'covers/hujan.jpg',
            ],
            [
                'title' => 'Solo Leveling (Vol. 1)',
                'author' => 'Chugong',
                'genre' => 'Fantasi',
                'description' => 'Ketika gerbang misterius menghubungkan dunia manusia dengan monster, segelintir orang terbangun dengan kekuatan gaib yang disebut Hunter. Sung Jin-woo dikenal sebagai "Hunter Terlemah di Dunia". Terjebak dalam Double Dungeon mematikan, Jin-woo menyelesaikan quest rahasia dan menjadi satu-satunya orang yang bisa "Level Up" tanpa batas.',
                'cover' => 'covers/solo_leveling.jpg',
            ],
            [
                'title' => 'Gadis Kretek',
                'author' => 'Ratih Kumala',
                'genre' => 'Fiksi Sejarah',
                'description' => 'Romansa berlatar sejarah industri rokok kretek di Jawa era pasca kemerdekaan. Ketika Soeraja, pemilik pabrik Kretek Djagad Raja sekarat, ia terus memanggil nama Jeng Yah—gadis masa lalunya yang jenius dalam meracik saus kretek. Ketiga anak laki-lakinya berlomba mencari jejak Jeng Yah ke pelosok Jawa Tengah, membongkar rahasia keluarga, cinta terlarang, dan intrik bisnis kretek.',
                'cover' => 'covers/gadis_kretek.jpg',
            ],
            [
                'title' => 'Pulang',
                'author' => 'Leila S. Chudori',
                'genre' => 'Fiksi Sejarah',
                'description' => 'Mengisahkan Dimas Suryo, wartawan eksil Indonesia pasca peristiwa 1965 yang terdampar di Paris dan mendirikan Restoran Tanah Air bersama rekan-rekannya sesama eksil politik. Berpuluh tahun kemudian, putrinya, Lintang Utara, kembali ke Jakarta untuk membuat film dokumenter tentang para korban dan masa kelam yang dihilangkan dari buku sejarah.',
                'cover' => 'covers/pulang.jpg',
            ],
            [
                'title' => 'Supernova: Ksatria, Puteri, dan Bintang Jatuh',
                'author' => 'Dee Lestari',
                'genre' => 'Fiksi Ilmiah',
                'description' => 'Mahakarya debut Dee Lestari yang memadukan sains modern, teori fraktal, psikologi, sufisme, dan kisah cinta segitiga tak biasa antara Ferre, Rana, dan Arwin. Di tempat lain, Dimas dan Reuben di Washington DC menciptakan dongeng sains romantik yang tanpa mereka sadari terjalin erat dengan kehidupan nyata di Jakarta.',
                'cover' => 'covers/supernova.jpg',
            ],
            [
                'title' => 'Ronggeng Dukuh Paruk',
                'author' => 'Ahmad Tohari',
                'genre' => 'Sastra',
                'description' => 'Tragedi kehidupan Srintil, gadis yatim piatu di desa terpencil Dukuh Paruk yang diyakini mewarisi roh magis Ki Secamenggala dan terpilih menjadi penari ronggeng pujaan desa. Di balik pesona selendang dan riuh tepuk kendang, tersimpan pergulatan batin, cinta tulus Rasus sang sahabat masa kecil, dan hancurnya martabat manusia saat desanya terseret badai pergolakan politik 1965.',
                'cover' => 'covers/ronggeng_dukuh_paruk.jpg',
            ],
            [
                'title' => 'Tenggelamnya Kapal Van Der Wijck',
                'author' => 'Buya Hamka',
                'genre' => 'Sastra Klasik',
                'description' => 'Roman klasik menyayat hati karya ulama besar Buya Hamka tentang cinta tak sampai antara Zainuddin—pemuda yatim piatu berdarah campuran Minang-Bugis—dan Hayati, mawar jelita keturunan bangsawan suku Minangkabau. Terbentur adat kaku dan ketimpangan harta, Zainuddin menjelma menjadi sastrawan tersohor di Jawa, hingga takdir mempertemukan mereka kembali dalam tragedi karamnya kapal megah Van Der Wijck.',
                'cover' => 'covers/tenggelamnya_kapal_van_der_wijck.jpg',
            ],
            [
                'title' => 'Norwegian Wood',
                'author' => 'Haruki Murakami',
                'genre' => 'Sastra Dunia',
                'description' => 'Berlatar di Tokyo era 1960-an di tengah pergolakan protes mahasiswa, Toru Watanabe dihantui oleh kenangan cinta pertamanya, Naoko, yang rapuh secara emosional setelah kematian tragis sahabat masa kecil mereka, Kizuki. Saat Naoko menarik diri ke sanatorium di perbukitan sunyi Kyoto, Toru bertemu Midori—gadis lincah, berani, dan penuh gairah hidup yang menariknya kembali ke dunia nyata.',
                'cover' => 'covers/norwegian_wood.jpg',
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'genre' => 'Distopia',
                'description' => 'Gambaran distopia paling menakutkan tentang rezim totaliter Oceania yang dipimpin oleh Big Brother dan Partai Angsoc. Di bawah pengawasan omnipresent Telescreen dan Polisi Pikiran (Thought Police), sejarah dipalsukan dan bahasa direduksi menjadi Newspeak. Winston Smith, pekerja di Kementerian Kebenaran, diam-diam memberontak dengan mencatat kebenaran dan menjalin cinta terlarang bersama Julia.',
                'cover' => 'covers/1984.jpg',
            ],
            [
                'title' => 'Almond',
                'author' => 'Sohn Won-pyung',
                'genre' => 'Fiksi',
                'description' => 'Yunjae lahir dengan kondisi otak langka yang disebut Alexithymia—amigdala di otaknya berukuran sekecil almond, membuatnya tidak mampu merasakan emosi apa pun seperti takut, marah, sedih, atau cinta. Ibunya mengajarinya tersenyum dan mengucapkan terima kasih lewat catatan di dinding. Ketika tragedi merenggut keluarganya, Yunjae bertemu Gon, anak pemberontak penuh luka batin yang perlahan membuka dunia perasaannya.',
                'cover' => 'covers/almond.jpg',
            ],
            [
                'title' => 'The Little Prince',
                'author' => 'Antoine de Saint-Exupéry',
                'genre' => 'Klasik Dunia',
                'description' => 'Dongeng filosofis abadi tentang seorang penerbang yang terdampar di padang pasir Sahara dan bertemu dengan seorang pangeran cilik yang datang dari asteroid kecil B-612. Lewat kisah perjalanan sang pangeran melintasi berbagai planet dan persahabatannya dengan seekor rubah, buku ini mengingatkan kita pada pesan mendalam: "Hanya dengan hati seseorang dapat melihat dengan benar; apa yang terpenting tidak tampak oleh mata."',
                'cover' => 'covers/the_little_prince.jpg',
            ],
            [
                'title' => 'Berani Tidak Disukai',
                'author' => 'Ichiro Kishimi & Fumitake Koga',
                'genre' => 'Filsafat',
                'description' => 'Ditulis dalam bentuk dialog mendalam antara seorang filsuf bijak dan seorang pemuda yang penuh rasa kecewa terhadap hidup. Menggunakan prinsip psikologi Alfred Adler—salah satu dari tiga raksasa psikologi abad ke-19 bersama Freud dan Jung—buku ini membongkar mitos trauma masa lalu dan mengajarkan pemisahan tugas (separation of tasks) demi meraih kebebasan sejati tanpa takut dihakimi orang lain.',
                'cover' => 'covers/berani_tidak_disukai.jpg',
            ],
            [
                'title' => 'Sebuah Seni untuk Bersikap Bodo Amat',
                'author' => 'Mark Manson',
                'genre' => 'Self-Dev',
                'description' => 'Pendekatan realistis tanpa basa-basi motivasi klise (toxic positivity). Mark Manson menegaskan bahwa kunci hidup tenang bukan memaksakan diri selalu positif, melainkan memilih hal-hal apa saja yang benar-benar layak kita pedulikan. Buku ini mengajarkan kita menerima keterbatasan, merangkul kegagalan, dan menemukan makna sejati di tengah penderitaan yang tak terelakkan.',
                'cover' => 'covers/sebuah_seni_bersikap_bodo_amat.jpg',
            ],
            [
                'title' => 'Jujutsu Kaisen (Vol. 1)',
                'author' => 'Gege Akutami',
                'genre' => 'Manga',
                'description' => 'Yuji Itadori, siswa SMA dengan bakat atletik luar biasa, tanpa sengaja terlibat dalam kutukan maut klub ilmu gaib. Untuk menyelamatkan teman-temannya dari roh kutukan jahat, Yuji menelan jari pusaka berkekuatan iblis milik Sukuna sang Raja Kutukan. Terseret ke dunia penyihir jujutsu di bawah bimbingan Satoru Gojo, Yuji harus belajar mengendalikan kekuatan terkutuk sebelum dieksekusi mati.',
                'cover' => 'covers/jujutsu_kaisen.jpg',
            ],
            [
                'title' => 'Death Note (Vol. 1)',
                'author' => 'Tsugumi Ohba & Takeshi Obata',
                'genre' => 'Manga',
                'description' => 'Light Yagami, siswa jenius di Tokyo, menemukan buku catatan hitam milik Shinigami Ryuk berjuluk Death Note. Siapa pun yang namanya tertulis di dalam buku tersebut akan mati karena serangan jantung. Berniat menciptakan dunia tanpa kejahatan sebagai "Kira", Light memulai duel adu kecerdasan mematikan melawan detektif eksentrik paling misterius di dunia yang hanya dikenal dengan inisial "L".',
                'cover' => 'covers/death_note.jpg',
            ],
        ];

        foreach ($newBooksData as $bData) {
            Book::firstOrCreate(['title' => $bData['title']], $bData);
        }

        $allBooks = Book::all();
        $bookMap = $allBooks->keyBy('title');

        // -----------------------------------------------------------------
        // 2. 15 MEMBER PERSONA REALISTIS DENGAN BIO, AVATAR, & GENRE FAVORIT
        // -----------------------------------------------------------------
        $membersData = [
            [
                'name' => 'Dimas Arya Pratama',
                'email' => 'dimas.arya@rebook.com',
                'avatar' => 'avatars/member_avatar_1.jpg',
                'bio' => 'Pecinta manga seinen & dark fantasy. Terobsesi dengan sinematografi panel Tatsuki Fujimoto dan eksplorasi psikologi karakter fiksi.',
                'favorite_genres' => ['Manga', 'Fantasi', 'Fiksi', 'Psychology'],
            ],
            [
                'name' => 'Nabila Safira',
                'email' => 'nabila.safira@rebook.com',
                'avatar' => 'avatars/member_avatar_2.jpg',
                'bio' => 'Mahasiswi sastra Indonesia. Senang membaca fiksi sejarah, novel realisme magis, dan karya-karya yang bersuara lantang tentang kemanusiaan.',
                'favorite_genres' => ['Sastra', 'Fiksi Sejarah', 'Novel', 'Sejarah'],
            ],
            [
                'name' => 'Kevin Sanjaya',
                'email' => 'kevin.tech@rebook.com',
                'avatar' => 'avatars/member_avatar_3.jpg',
                'bio' => 'Software engineer di siang hari, pembaca buku stoisisme dan behavioral economics di malam hari. Selalu mencari pola di balik kebiasaan manusia.',
                'favorite_genres' => ['Self-Dev', 'Filsafat', 'Technology', 'Psychology'],
            ],
            [
                'name' => 'Annisa Putri Rahayu',
                'email' => 'annisa.putri@rebook.com',
                'avatar' => 'avatars/member_avatar_4.jpg',
                'bio' => 'Kolektor novel Tere Liye dan kisah-kisah coming-of-age. Suka secangkir kopi hangat ditemani buku yang menguras air mata.',
                'favorite_genres' => ['Fiksi Ilmiah', 'Novel', 'Romance', 'Fiksi'],
            ],
            [
                'name' => 'Rian Hidayat',
                'email' => 'rian.hidayat@rebook.com',
                'avatar' => 'avatars/member_avatar_5.jpg',
                'bio' => 'Komikus indie & ilustrator. Meneliti alur paneling, visual storytelling, dan pace narasi manga modern seperti Look Back dan Chainsaw Man.',
                'favorite_genres' => ['Manga', 'Sastra', 'Fiksi', 'Education'],
            ],
            [
                'name' => 'Sarah Maharani',
                'email' => 'sarah.maharani@rebook.com',
                'avatar' => 'avatars/member_avatar_6.jpg',
                'bio' => 'Pengamat psikologi perkembangan. Gemar menelaah buku non-fiksi memoar dan biografi orang-orang yang bangkit dari trauma.',
                'favorite_genres' => ['Non-Fiksi', 'Psychology', 'Biography', 'Self-Dev'],
            ],
            [
                'name' => 'Fajar Ramadhan',
                'email' => 'fajar.ramadhan@rebook.com',
                'avatar' => 'avatars/member_avatar_7.jpg',
                'bio' => 'Penggemar novel fantasi tingkat akut dan shonen/seinen manga. Menanti setiap rilis volume manga fisik dengan antusias.',
                'favorite_genres' => ['Manga', 'Fantasi', 'Fiksi'],
            ],
            [
                'name' => 'Dinda Kirana',
                'email' => 'dinda.kirana@rebook.com',
                'avatar' => 'avatars/member_avatar_8.jpg',
                'bio' => 'Penikmat cerita slice-of-life, kisah cinta penuh emosi, dan eksplorasi perjalanan hidup. Reviewer santai di media sosial.',
                'favorite_genres' => ['Novel', 'Fiksi Ilmiah', 'Romance', 'Sastra'],
            ],
            [
                'name' => 'Rendy Oktavian',
                'email' => 'rendy.oktavian@rebook.com',
                'avatar' => 'avatars/member_avatar_9.jpg',
                'bio' => 'Mencoba menerapkan prinsip dikotomi kendali Epiktetos dalam dunia modern. Pembaca setia Morgan Housel dan Yuval Noah Harari.',
                'favorite_genres' => ['Filsafat', 'Self-Dev', 'Sejarah', 'Business'],
            ],
            [
                'name' => 'Tiara Andini',
                'email' => 'tiara.andini@rebook.com',
                'avatar' => 'avatars/member_avatar_10.jpg',
                'bio' => 'Booktuber & podcaster literasi. Berkelana dari sastra kanonik Indonesia hingga graphic novel kontemporer.',
                'favorite_genres' => ['Manga', 'Sastra', 'Fiksi Sejarah', 'Novel'],
            ],
            [
                'name' => 'Gilang Permana',
                'email' => 'gilang.permana@rebook.com',
                'avatar' => 'avatars/member_avatar_11.jpg',
                'bio' => 'Pecinta kisah penuh teka-teki, thriller psikologis, dan drama supernatural. Suka twist ending yang tidak tertebak.',
                'favorite_genres' => ['Manga', 'Thriller', 'Mystery', 'Psychology'],
            ],
            [
                'name' => 'Maya Anggraini',
                'email' => 'maya.anggraini@rebook.com',
                'avatar' => 'avatars/member_avatar_12.jpg',
                'bio' => 'Guru Bahasa dan Sastra. Percaya bahwa membaca fiksi adalah cara terbaik melatih empati dan memahami jiwa manusia.',
                'favorite_genres' => ['Sastra', 'Education', 'Fiksi Sejarah', 'Novel'],
            ],
            [
                'name' => 'Bagas Wicaksono',
                'email' => 'bagas.wicaksono@rebook.com',
                'avatar' => 'avatars/member_avatar_13.jpg',
                'bio' => 'Hunter rank S di kamar tidur. Penikmat alur zero-to-hero, world building megah, dan pertarungan taktis dalam novel aksi.',
                'favorite_genres' => ['Fantasi', 'Manga', 'Fiksi'],
            ],
            [
                'name' => 'Clarissa Devi',
                'email' => 'clarissa.devi@rebook.com',
                'avatar' => 'avatars/member_avatar_14.jpg',
                'bio' => 'Editor naskah penerbitan. Menghabiskan hari dengan menelisik diksi, ritme kalimat, dan resonansi emosional dalam tulisan.',
                'favorite_genres' => ['Sastra', 'Fiksi', 'Non-Fiksi', 'Biography'],
            ],
            [
                'name' => 'Ilham Saputra',
                'email' => 'ilham.saputra@rebook.com',
                'avatar' => 'avatars/member_avatar_15.jpg',
                'bio' => 'Penikmat sejarah nusantara dan dinamika peradaban manusia. Sering merenung berjam-jam setelah menyelesaikan karya Pram dan Harari.',
                'favorite_genres' => ['Sejarah', 'Fiksi Sejarah', 'Filsafat', 'Non-Fiksi'],
            ],
        ];

        foreach ($membersData as $mData) {
            User::updateOrCreate(
                ['email' => $mData['email']],
                [
                    'name' => $mData['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'member',
                    'status' => 'Aktif',
                    'avatar' => $mData['avatar'],
                    'bio' => $mData['bio'],
                    'favorite_genres' => $mData['favorite_genres'],
                ]
            );
        }

        $allMembers = User::where('role', 'member')->get();
        $userMap = $allMembers->keyBy('email');

        // -----------------------------------------------------------------
        // 3. SETEL PAJANGAN 4 BUKU FAVORIT ALA LETTERBOXD SECARA ORGANIK
        // -----------------------------------------------------------------
        $userTop4Mapping = [
            'dimas.arya@rebook.com' => ['Norwegian Wood', 'Look Back', 'Pulang', 'Chainsaw Man (Vol. 1)'],
            'nabila.safira@rebook.com' => ['Gadis Kretek', 'Laut Bercerita', 'Cantik Itu Luka', 'Bumi Manusia'],
            'kevin.tech@rebook.com' => ['Supernova: Ksatria, Puteri, dan Bintang Jatuh', '1984', 'Atomic Habits', 'The Psychology of Money'],
            'annisa.putri@rebook.com' => ['Almond', 'Laskar Pelangi', 'Tenggelamnya Kapal Van Der Wijck', 'Laut Bercerita'],
            'rian.hidayat@rebook.com' => ['Death Note (Vol. 1)', 'Jujutsu Kaisen (Vol. 1)', 'Look Back', 'Chainsaw Man (Vol. 1)'],
            'sarah.maharani@rebook.com' => ['Berani Tidak Disukai', 'Educated', "Man's Search for Meaning", 'Thinking, Fast and Slow'],
            'fajar.ramadhan@rebook.com' => ['Solo Leveling (Vol. 1)', 'Jujutsu Kaisen (Vol. 1)', 'Death Note (Vol. 1)', 'Look Back'],
            'dinda.kirana@rebook.com' => ['The Little Prince', 'Laut Bercerita', 'Almond', 'Hujan'],
            'rendy.oktavian@rebook.com' => ['Berani Tidak Disukai', 'Filosofi Teras', 'Sapiens', 'The Psychology of Money'],
            'tiara.andini@rebook.com' => ['Norwegian Wood', 'Gadis Kretek', 'Cantik Itu Luka', 'Goodbye, Eri'],
            'gilang.permana@rebook.com' => ['Sebuah Seni untuk Bersikap Bodo Amat', 'Death Note (Vol. 1)', 'Chainsaw Man (Vol. 1)', 'Cantik Itu Luka'],
            'maya.anggraini@rebook.com' => ['Gadis Kretek', 'Ronggeng Dukuh Paruk', 'Bumi Manusia', 'Laut Bercerita'],
            'bagas.wicaksono@rebook.com' => ['Jujutsu Kaisen (Vol. 1)', 'Solo Leveling (Vol. 1)', "Frieren: Beyond Journey's End", 'Chainsaw Man (Vol. 1)'],
            'clarissa.devi@rebook.com' => ['The Little Prince', 'Supernova: Ksatria, Puteri, dan Bintang Jatuh', 'Cantik Itu Luka', 'Laut Bercerita'],
            'ilham.saputra@rebook.com' => ['1984', 'Pulang', 'Bumi Manusia', 'Sapiens'],
            'member@rebook.com' => ['Gadis Kretek', 'Norwegian Wood', 'Filosofi Teras', 'Laut Bercerita'],
        ];

        foreach ($allMembers as $m) {
            $topTitles = $userTop4Mapping[$m->email] ?? null;
            if ($topTitles) {
                $favIds = [];
                foreach ($topTitles as $title) {
                    if (isset($bookMap[$title])) {
                        $favIds[] = $bookMap[$title]->id;
                    }
                }
                $m->favoriteBooks()->sync($favIds);
            } else {
                $favIds = $allBooks->random(min(4, $allBooks->count()))->pluck('id')->toArray();
                $m->favoriteBooks()->sync($favIds);
            }
        }

        // -----------------------------------------------------------------
        // 4. RAK BACAAN MEMBER DENGAN STATUS & WAKTU BERTAHAP
        // -----------------------------------------------------------------
        $baseDate = Carbon::now();
        foreach ($allMembers as $m) {
            $sampleBooks = $allBooks->random(rand(4, 8));
            $statuses = [
                ReadingShelf::STATUS_READ,
                ReadingShelf::STATUS_READ,
                ReadingShelf::STATUS_CURRENTLY_READING,
                ReadingShelf::STATUS_WANT_TO_READ,
            ];

            foreach ($sampleBooks as $idx => $b) {
                $st = $statuses[$idx % count($statuses)];
                $shelfDaysAgo = rand(10, 300);
                ReadingShelf::updateOrCreate(
                    ['user_id' => $m->id, 'book_id' => $b->id],
                    [
                        'status' => $st,
                        'created_at' => $baseDate->copy()->subDays($shelfDaysAgo)->subHours(rand(1, 23)),
                        'updated_at' => $baseDate->copy()->subDays(max(1, $shelfDaysAgo - rand(5, 20))),
                    ]
                );
            }
        }

        // -----------------------------------------------------------------
        // 5. BUKU DISUKAI (BOOK LIKES)
        // -----------------------------------------------------------------
        foreach ($allMembers as $m) {
            $likedBookIds = $allBooks->random(rand(4, 9))->pluck('id')->toArray();
            $m->likedBooks()->syncWithoutDetaching($likedBookIds);
        }

        // -----------------------------------------------------------------
        // 6. ULASAN ORGANIK GAYA LETTERBOXD: KOMBINASI ANALISIS, RELATABLE & WITTY ONE-LINER
        // DENGAN RATING SETENGAH BINTANG (3.5, 4.0, 4.5, 5.0) & TANGGAL BERTAHAP
        // -----------------------------------------------------------------
        $organicReviews = [
            // Chainsaw Man
            [
                'book' => 'Chainsaw Man (Vol. 1)',
                'email' => 'dimas.arya@rebook.com',
                'rating' => 5.0,
                'days_ago' => 280,
                'content' => 'Tatsuki Fujimoto benar-benar gila! Pacingnya sangat cepat, tanpa basa-basi klise shonen. Denji bukan tipikal protagonis suci yang ingin menyelamatkan dunia, tujuannya sederhana dan manusiawi banget (sarapan roti pakai selai). Visual iblisnya grotesk dan penuh gaya sinematik.',
            ],
            [
                'book' => 'Chainsaw Man (Vol. 1)',
                'email' => 'rian.hidayat@rebook.com',
                'rating' => 4.5,
                'days_ago' => 190,
                'content' => 'Sebagai ilustrator, saya kagum luar biasa dengan paneling Fujimoto. Alur aksinya terbaca seperti gerakan kamera bioskop 35mm. Karakter Makima memberi aura mencekam sejak kemunculan pertamanya.',
            ],
            [
                'book' => 'Chainsaw Man (Vol. 1)',
                'email' => 'gilang.permana@rebook.com',
                'rating' => 4.0,
                'days_ago' => 85,
                'content' => 'Berdarah-darah, liar, tapi anehnya punya hati di hubungan Denji dan Pochita. Ceritanya absurd tapi bikin ketagihan buat langsung maraton volume lanjutannya.',
            ],
            [
                'book' => 'Chainsaw Man (Vol. 1)',
                'email' => 'fajar.ramadhan@rebook.com',
                'rating' => 4.5,
                'days_ago' => 14,
                'content' => 'Denji is literally me except the chainsaw part. Energi chaos murni dari Fujimoto yang gak bakal kamu temukan di manga mainstream lainnya.',
            ],
            [
                'book' => 'Chainsaw Man (Vol. 1)',
                'email' => 'maya.anggraini@rebook.com',
                'rating' => 3.5,
                'days_ago' => 5,
                'content' => 'Bagi pembaca umum mungkin terasa terlalu vulgar dan penuh kekerasan di beberapa adegan. Tapi harus diakui penokohan Denji adalah dekonstruksi menarik dari kiasan pahlawan shonen konvensional.',
            ],

            // Goodbye, Eri
            [
                'book' => 'Goodbye, Eri',
                'email' => 'tiara.andini@rebook.com',
                'rating' => 5.0,
                'days_ago' => 310,
                'content' => 'Masterpiece one-shot! Cara Fujimoto membagi halaman menjadi panel 4-grid statis seperti bingkai rekaman smartphone benar-benar brilian. Twist fantasi di endingnya membuat saya terdiam 10 menit setelah membaca. Sebuah surat cinta untuk perfilman.',
            ],
            [
                'book' => 'Goodbye, Eri',
                'email' => 'dimas.arya@rebook.com',
                'rating' => 4.5,
                'days_ago' => 210,
                'content' => 'Karya terbaik Fujimoto sejauh ini melebihi CSM bagi saya. Hubungan antara Yuta dan Eri mempermainkan persepsi pembaca: mana yang dokumenter jujur, mana yang rekayasa kamera. Ending ledakannya sangat ikonik.',
            ],
            [
                'book' => 'Goodbye, Eri',
                'email' => 'dinda.kirana@rebook.com',
                'rating' => 4.0,
                'days_ago' => 120,
                'content' => 'Sangat menguras emosi dan sedikit membingungkan pada awalnya, tapi setelah membaca ulang bagian akhirnya, semua potongan puzzle terasa pas dan puitis.',
            ],
            [
                'book' => 'Goodbye, Eri',
                'email' => 'gilang.permana@rebook.com',
                'rating' => 4.5,
                'days_ago' => 20,
                'content' => 'Gue natap langit-langit kamar 40 menit habis beres baca halaman terakhirnya. Sinematik banget tolong dibikin adaptasi film layar lebar!',
            ],

            // Look Back
            [
                'book' => 'Look Back',
                'email' => 'rian.hidayat@rebook.com',
                'rating' => 5.0,
                'days_ago' => 340,
                'content' => 'Adegan Fujino berjalan menari di tengah hujan setelah dipuji Kyomoto adalah salah satu momen paling murni dan indah dalam sejarah manga. Cerita ini begitu personal bagi siapa pun yang pernah bergelut dengan karya seni dan rasa kesepian.',
            ],
            [
                'book' => 'Look Back',
                'email' => 'annisa.putri@rebook.com',
                'rating' => 5.0,
                'days_ago' => 160,
                'content' => 'Air mata saya tumpah tanpa terasa. Tragedi yang diangkat begitu menusuk, tapi endingnya mengingatkan kita mengapa kita harus terus melangkah dan melihat ke belakang dengan penuh rasa syukur.',
            ],
            [
                'book' => 'Look Back',
                'email' => 'clarissa.devi@rebook.com',
                'rating' => 4.5,
                'days_ago' => 45,
                'content' => 'Sederhana, padat, dan tidak ada satu panel pun yang terbuang sia-sia. Fujimoto membuktikan bahwa dia bukan cuma jago bikin komedi gore, tapi juga drama kemanusiaan tingkat tinggi.',
            ],
            [
                'book' => 'Look Back',
                'email' => 'fajar.ramadhan@rebook.com',
                'rating' => 4.5,
                'days_ago' => 3,
                'content' => '10/10 would cry on public transport again. Masterpiece.',
            ],

            // Frieren: Beyond Journey's End
            [
                'book' => "Frieren: Beyond Journey's End",
                'email' => 'fajar.ramadhan@rebook.com',
                'rating' => 5.0,
                'days_ago' => 240,
                'content' => 'Pendekatan fantasi yang sangat tenang dan mendalam. Fokusnya bukan pada mengalahkan musuh, melainkan perjalanan melankolis mengenang teman-teman yang telah tiada. World-building nya halus dan menenangkan jiwa.',
            ],
            [
                'book' => "Frieren: Beyond Journey's End",
                'email' => 'bagas.wicaksono@rebook.com',
                'rating' => 4.5,
                'days_ago' => 110,
                'content' => 'Pertarungan sihirnya taktis tanpa banyak teriakan nama jurus. Hubungan Frieren dan Fern sangat manis, seperti guru dan murid sekaligus ibu dan anak.',
            ],
            [
                'book' => "Frieren: Beyond Journey's End",
                'email' => 'rian.hidayat@rebook.com',
                'rating' => 4.0,
                'days_ago' => 18,
                'content' => 'Frieren mengajarkan kita bahwa elf pun bisa nyesel seumur hidup gara-gara gak confess lebih awal. Melankolis tapi sangat hangat.',
            ],

            // Laut Bercerita
            [
                'book' => 'Laut Bercerita',
                'email' => 'nabila.safira@rebook.com',
                'rating' => 5.0,
                'days_ago' => 350,
                'content' => 'Membaca Laut Bercerita seperti dicekik pelan-pelan oleh rasa duka yang tak berkesudahan. Diksi Leila S. Chudori sangat puitis namun menusuk tajam. Bagian narasi Asmara Jati di Aksi Kamisan membuat dada terasa sesak. Wajib dibaca oleh seluruh generasi muda!',
            ],
            [
                'book' => 'Laut Bercerita',
                'email' => 'maya.anggraini@rebook.com',
                'rating' => 5.0,
                'days_ago' => 230,
                'content' => 'Sebuah monumen sastra untuk para korban penghilangan paksa 1998. Buku ini mengembalikan martabat dan nama-nama yang berusaha dihapus oleh sejarah penguasa.',
            ],
            [
                'book' => 'Laut Bercerita',
                'email' => 'ilham.saputra@rebook.com',
                'rating' => 4.5,
                'days_ago' => 140,
                'content' => 'Riset sejarahnya luar biasa detail. Persahabatan di Rumah Klender, aroma tenggiri goreng di Blangguan, dan dinginnya dasar laut yang sunyi. Sangat menyayat hati.',
            ],
            [
                'book' => 'Laut Bercerita',
                'email' => 'kevin.tech@rebook.com',
                'rating' => 4.0,
                'days_ago' => 60,
                'content' => 'Buku yang membuka mata saya tentang harga mahal demokrasi yang kita nikmati saat ini. Bagian awal sedikit lambat, tetapi begitu masuk bab penyekapan, sulit untuk berhenti membaca.',
            ],
            [
                'book' => 'Laut Bercerita',
                'email' => 'clarissa.devi@rebook.com',
                'rating' => 4.5,
                'days_ago' => 7,
                'content' => 'Menolak lupa, merawat ingatan. Buku yang bikin kamu gak bisa tidur semalaman memikirkan nasib para aktivis.',
            ],

            // Cantik Itu Luka
            [
                'book' => 'Cantik Itu Luka',
                'email' => 'clarissa.devi@rebook.com',
                'rating' => 5.0,
                'days_ago' => 300,
                'content' => "Opening line novel ini adalah salah satu yang terhebat dalam sastra Indonesia modern: 'Suatu sore di akhir pekan di bulan Maret, Dewi Ayu bangkit dari kuburnya setelah dua puluh satu tahun mati.' Eka Kurniawan memadukan kegilaan ala Gabriel Garcia Marquez dengan folklor dan trauma sejarah nusantara. Liar, berani, dan tak terlupakan.",
            ],
            [
                'book' => 'Cantik Itu Luka',
                'email' => 'nabila.safira@rebook.com',
                'rating' => 4.5,
                'days_ago' => 175,
                'content' => 'Dunia Halimunda sangat memikat. Karakter Dewi Ayu sangat kompleks dan tegar di tengah takdirnya yang tragis. Beberapa bagian memang sangat vulgar dan gelap, jadi perhatikan trigger warning sebelum membaca.',
            ],
            [
                'book' => 'Cantik Itu Luka',
                'email' => 'gilang.permana@rebook.com',
                'rating' => 4.0,
                'days_ago' => 25,
                'content' => 'Realisme magis paling edan dari Indonesia. Dongeng yang penuh satir tajam soal imperialisme dan kepalsuan moral manusia.',
            ],

            // Filosofi Teras
            [
                'book' => 'Filosofi Teras',
                'email' => 'rendy.oktavian@rebook.com',
                'rating' => 5.0,
                'days_ago' => 330,
                'content' => 'Buku pengubah hidup! Konsep Dikotomi Kendali berhasil menyembuhkan kecemasan saya terhadap masa depan dan karier. Penjelasan Om Piring sangat membumi dengan contoh kasus khas warga Jakarta yang kena macet dan drama kantor.',
            ],
            [
                'book' => 'Filosofi Teras',
                'email' => 'kevin.tech@rebook.com',
                'rating' => 4.5,
                'days_ago' => 195,
                'content' => 'Buku pengantar Stoicism terbaik dalam bahasa Indonesia. Ilustrasi dan diagramnya sangat membantu pemahaman. Sangat direkomendasikan untuk siapa pun yang sering overthinking.',
            ],
            [
                'book' => 'Filosofi Teras',
                'email' => 'sarah.maharani@rebook.com',
                'rating' => 4.0,
                'days_ago' => 90,
                'content' => 'Banyak keselarasan antara ajaran Stoa di buku ini dengan Cognitive Behavioral Therapy (CBT). Bahasa komunikatif membuatnya mudah dicerna orang awam tanpa merasa digurui.',
            ],
            [
                'book' => 'Filosofi Teras',
                'email' => 'member@rebook.com',
                'rating' => 4.5,
                'days_ago' => 12,
                'content' => 'Buku wajib anak muda yang suka overthinking jam 2 pagi. Belajar fokus ke hal yang bisa dikontrol doang.',
            ],

            // Hujan
            [
                'book' => 'Hujan',
                'email' => 'annisa.putri@rebook.com',
                'rating' => 5.0,
                'days_ago' => 270,
                'content' => 'Kisah Lail dan Esok selalu berhasil membuat hati bergetar. Tere Liye memadukan premis sci-fi tentang masa depan dengan kisah cinta yang sederhana dan tulus. Bagian akhirnya menguras air mata!',
            ],
            [
                'book' => 'Hujan',
                'email' => 'dinda.kirana@rebook.com',
                'rating' => 4.5,
                'days_ago' => 150,
                'content' => 'Buku yang sangat manis sekaligus pilu. Pesan moralnya kuat: bukan melupakan yang membuat kita damai, tapi menerima takdir dengan lapang dada.',
            ],
            [
                'book' => 'Hujan',
                'email' => 'dimas.arya@rebook.com',
                'rating' => 3.5,
                'days_ago' => 35,
                'content' => 'Premis sci-fi nya cukup menarik untuk novel remaja Indonesia, meskipun di beberapa bagian penyelesaian konfliknya terasa sedikit dramatis khas Tere Liye.',
            ],

            // Solo Leveling (Vol. 1)
            [
                'book' => 'Solo Leveling (Vol. 1)',
                'email' => 'bagas.wicaksono@rebook.com',
                'rating' => 5.0,
                'days_ago' => 220,
                'content' => 'Sensasi kepuasan membaca saat Sung Jin-woo mulai membantai monster dari hunter terlemah jadi overpowered tidak ada tandingannya. Sangat adiktif dan bikin dopamine melonjak!',
            ],
            [
                'book' => 'Solo Leveling (Vol. 1)',
                'email' => 'fajar.ramadhan@rebook.com',
                'rating' => 4.5,
                'days_ago' => 70,
                'content' => 'Aksinya cepat, ilustrasi covernya gahar, dan pacing dungeon-nya bikin tegang terus. Cocok banget buat yang butuh hiburan beroktan tinggi.',
            ],

            // Atomic Habits
            [
                'book' => 'Atomic Habits',
                'email' => 'kevin.tech@rebook.com',
                'rating' => 5.0,
                'days_ago' => 320,
                'content' => 'Konsep 1% lebih baik setiap hari dan penataan lingkungan (cue) bekerja nyata untuk saya. Buku non-fiksi paling praktis yang pernah saya terapkan dalam kehidupan kerja dan rutinitas harian.',
            ],
            [
                'book' => 'Atomic Habits',
                'email' => 'rendy.oktavian@rebook.com',
                'rating' => 4.5,
                'days_ago' => 200,
                'content' => 'Buku yang mengubah cara pandang saya dari mengejar target menjadi membangun identitas. Bukan sekadar motivasi kosong, tapi sistem sains perilaku yang dapat diuji.',
            ],
            [
                'book' => 'Atomic Habits',
                'email' => 'sarah.maharani@rebook.com',
                'rating' => 4.0,
                'days_ago' => 50,
                'content' => "Sangat terstruktur. Bab tentang 'habit stacking' dan aturan dua menit sangat aplikatif bahkan untuk orang yang paling malas sekalipun.",
            ],

            // Bumi Manusia
            [
                'book' => 'Bumi Manusia',
                'email' => 'ilham.saputra@rebook.com',
                'rating' => 5.0,
                'days_ago' => 360,
                'content' => 'Karya agung sastra dunia dari penjara Pulau Buru. Karakter Nyai Ontosoroh adalah lambang perlawanan dan kecerdasan pribumi yang tak tertandingi. Setiap orang Indonesia wajib membaca tetralogi ini.',
            ],
            [
                'book' => 'Bumi Manusia',
                'email' => 'maya.anggraini@rebook.com',
                'rating' => 5.0,
                'days_ago' => 215,
                'content' => 'Bahasa Pramoedya Ananta Toer begitu bertenaga dan membakar semangat. Kisah Minke dan Annelies adalah cerminan hukum kolonial yang timpang dan melukai kemanusiaan.',
            ],
            [
                'book' => 'Bumi Manusia',
                'email' => 'nabila.safira@rebook.com',
                'rating' => 4.5,
                'days_ago' => 80,
                'content' => "'Kita telah melawan, Nak, Noni. Sebaik-baiknya, sehormat-hormatnya.' Kalimat penutup yang abadi dan bikin merinding setiap kali dibaca ulang.",
            ],

            // Sapiens
            [
                'book' => 'Sapiens',
                'email' => 'ilham.saputra@rebook.com',
                'rating' => 5.0,
                'days_ago' => 290,
                'content' => 'Cara Harari menjelaskan bahwa kemampuan menciptakan fiksi/mitos bersama (uang, negara, agama) adalah kunci dominasi Homo sapiens di bumi benar-benar membalikkan logika sejarah konvensional.',
            ],
            [
                'book' => 'Sapiens',
                'email' => 'rendy.oktavian@rebook.com',
                'rating' => 4.5,
                'days_ago' => 135,
                'content' => 'Bab tentang Revolusi Kognitif dan Revolusi Pertanian sangat mencerahkan. Ternyata kita bukan menjinakkan gandum, tapi gandum yang menjinakkan kita.',
            ],

            // Educated
            [
                'book' => 'Educated',
                'email' => 'sarah.maharani@rebook.com',
                'rating' => 5.0,
                'days_ago' => 250,
                'content' => 'Memoar Tara Westover sangat mencekam sekaligus menginspirasi. Bagaimana pendidikan formal mampu membebaskan seseorang dari kungkungan fanatisme keluarga yang mengisolasi.',
            ],

            // The Psychology of Money
            [
                'book' => 'The Psychology of Money',
                'email' => 'rendy.oktavian@rebook.com',
                'rating' => 5.0,
                'days_ago' => 305,
                'content' => 'Buku finansial yang tidak membosankan dengan angka rumit. Morgan Housel mengajarkan bahwa kesuksesan finansial lebih berkaitan dengan perilaku psikologis daripada kepintaran matematika.',
            ],
            [
                'book' => 'The Psychology of Money',
                'email' => 'kevin.tech@rebook.com',
                'rating' => 4.5,
                'days_ago' => 105,
                'content' => "Bab 'Kekayaan adalah apa yang tidak Anda lihat' sangat menampar gaya hidup konsumtif zaman sekarang.",
            ],

            // Man's Search for Meaning
            [
                'book' => "Man's Search for Meaning",
                'email' => 'sarah.maharani@rebook.com',
                'rating' => 5.0,
                'days_ago' => 315,
                'content' => 'Viktor Frankl membuktikan bahwa dalam penderitaan paling tak manusiawi sekalipun di kamp Auschwitz, kebebasan terakhir manusia adalah memilih sikap batinnya sendiri. Buku klasik yang sangat agung.',
            ],
            [
                'book' => "Man's Search for Meaning",
                'email' => 'gilang.permana@rebook.com',
                'rating' => 4.5,
                'days_ago' => 115,
                'content' => 'Singkat tapi sarat makna mendalam. Logoterapi Frankl memberi pemahaman baru tentang tujuan hidup.',
            ],

            // Gadis Kretek
            [
                'book' => 'Gadis Kretek',
                'email' => 'maya.anggraini@rebook.com',
                'rating' => 5.0,
                'days_ago' => 240,
                'content' => 'Aroma cengkeh dan tembakau seakan tercium dari setiap lembarnya. Ratih Kumala sangat detail merangkum persaingan pabrik kretek di kota M dan kisah cinta Jeng Yah yang tragis. Jauh lebih kaya dan puitis dibanding adaptasi serialnya!',
            ],
            [
                'book' => 'Gadis Kretek',
                'email' => 'tiara.andini@rebook.com',
                'rating' => 4.5,
                'days_ago' => 60,
                'content' => 'Penokohan Jeng Yah luar biasa kuat sebagai perempuan peracik saus kretek di era yang sangat patriarki. Endingnya membuat dada sesak.',
            ],

            // Pulang
            [
                'book' => 'Pulang',
                'email' => 'ilham.saputra@rebook.com',
                'rating' => 5.0,
                'days_ago' => 295,
                'content' => 'Kisah para eksil politik Indonesia di Paris yang menghidupkan kenangan tanah air lewat aroma masakan di Restoran Tanah Air. Leila S. Chudori piawai merajut tragedi sejarah bangsa ke dalam potret keluarga yang intim.',
            ],
            [
                'book' => 'Pulang',
                'email' => 'dimas.arya@rebook.com',
                'rating' => 4.5,
                'days_ago' => 90,
                'content' => 'Buku yang mengajarkan arti rumah dan pulang. Latar Paris dan Jakarta era 1998 digambarkan begitu hidup dan sarat emosi.',
            ],

            // Supernova
            [
                'book' => 'Supernova: Ksatria, Puteri, dan Bintang Jatuh',
                'email' => 'kevin.tech@rebook.com',
                'rating' => 5.0,
                'days_ago' => 310,
                'content' => 'Karya sastra fiksi sains Indonesia paling revolusioner di eranya. Perpaduan teori fraktal matematika, fisika kuantum, dan filsafat cinta Dee Lestari masih tetap memukau dibaca dua dekade kemudian.',
            ],
            [
                'book' => 'Supernova: Ksatria, Puteri, dan Bintang Jatuh',
                'email' => 'clarissa.devi@rebook.com',
                'rating' => 4.5,
                'days_ago' => 75,
                'content' => 'Dinamika antara Ferre dan Rana begitu kompleks dan sensual, sementara percakapan Dimas dan Reuben selalu membuat otak tertantang.',
            ],

            // Ronggeng Dukuh Paruk
            [
                'book' => 'Ronggeng Dukuh Paruk',
                'email' => 'maya.anggraini@rebook.com',
                'rating' => 5.0,
                'days_ago' => 330,
                'content' => 'Ahmad Tohari melukiskan kehidupan pedesaan Jawa dan tradisi ronggeng dengan kejujuran sosiologis yang menawan sekaligus memilukan. Srintil adalah salah satu tokoh wanita paling bernyawa dalam kanon sastra Indonesia.',
            ],

            // Tenggelamnya Kapal Van Der Wijck
            [
                'book' => 'Tenggelamnya Kapal Van Der Wijck',
                'email' => 'annisa.putri@rebook.com',
                'rating' => 5.0,
                'days_ago' => 345,
                'content' => 'Surat-surat Zainuddin untuk Hayati adalah mahakarya prosa liris bahasa Melayu-Indonesia. Kritik Buya Hamka terhadap adat yang memberhalakan keturunan dan harta tersampaikan begitu tajam.',
            ],

            // Norwegian Wood
            [
                'book' => 'Norwegian Wood',
                'email' => 'tiara.andini@rebook.com',
                'rating' => 4.5,
                'days_ago' => 265,
                'content' => 'Murakami menangkap rasa hampa, duka, dan nostalgia masa muda dengan begitu intim. Musik The Beatles, hujan di Tokyo, dan kontras antara Naoko dan Midori.',
            ],
            [
                'book' => 'Norwegian Wood',
                'email' => 'dimas.arya@rebook.com',
                'rating' => 4.0,
                'days_ago' => 40,
                'content' => 'Melankolis, sunyi, dan penuh kesedihan yang membekas. "Kematian bukan lawan dari kehidupan, melainkan bagian dari kehidupan itu sendiri."',
            ],

            // 1984
            [
                'book' => '1984',
                'email' => 'ilham.saputra@rebook.com',
                'rating' => 5.0,
                'days_ago' => 355,
                'content' => 'Buku yang semakin menakutkan karena semakin banyak ramalan Orwell yang relevan di era pengawasan digital saat ini. Newspeak, Doublethink, dan Big Brother adalah peringatan abadi bagi umat manusia.',
            ],
            [
                'book' => '1984',
                'email' => 'kevin.tech@rebook.com',
                'rating' => 4.5,
                'days_ago' => 110,
                'content' => 'Mencekam dari halaman pertama sampai akhir. Tidak ada happy ending yang murahan, hanya kebenaran telanjang tentang kekuasaan.',
            ],

            // Almond
            [
                'book' => 'Almond',
                'email' => 'annisa.putri@rebook.com',
                'rating' => 5.0,
                'days_ago' => 215,
                'content' => 'Kisah Yunjae yang tidak bisa merasakan emosi sangat menyentuh hati. Dinamika hubungannya dengan Gon menunjukkan bahwa empati adalah sesuatu yang bisa dipelajari lewat kehadiran tulus.',
            ],
            [
                'book' => 'Almond',
                'email' => 'sarah.maharani@rebook.com',
                'rating' => 4.5,
                'days_ago' => 55,
                'content' => 'Pacingnya cepat dan hangat. Sangat mengharukan melihat bagaimana seseorang yang tidak bisa merasakan emosi justru menjadi orang yang paling tulus mendengarkan.',
            ],

            // The Little Prince
            [
                'book' => 'The Little Prince',
                'email' => 'clarissa.devi@rebook.com',
                'rating' => 5.0,
                'days_ago' => 360,
                'content' => 'Buku tipis yang harus dibaca ulang di setiap fase kedewasaan kita. Pelajaran tentang mawar, rubah yang dijinakkan, dan bagaimana orang dewasa sering lupa melihat dengan hati.',
            ],

            // Berani Tidak Disukai
            [
                'book' => 'Berani Tidak Disukai',
                'email' => 'rendy.oktavian@rebook.com',
                'rating' => 5.0,
                'days_ago' => 275,
                'content' => 'Psikologi Adlerian dalam format dialog filsuf-pemuda benar-benar mengubah cara saya memandang hubungan interpersonal. Konsep "pemisahan tugas" membebaskan saya dari overthinking ekspektasi orang lain.',
            ],
            [
                'book' => 'Berani Tidak Disukai',
                'email' => 'sarah.maharani@rebook.com',
                'rating' => 4.5,
                'days_ago' => 80,
                'content' => 'Bukan buku self-help biasa yang memberi janji manis. Buku ini menuntut keberanian untuk hidup di saat ini tanpa menyalahkan masa lalu.',
            ],

            // Sebuah Seni untuk Bersikap Bodo Amat
            [
                'book' => 'Sebuah Seni untuk Bersikap Bodo Amat',
                'email' => 'gilang.permana@rebook.com',
                'rating' => 4.0,
                'days_ago' => 195,
                'content' => 'Gaya bahasa Mark Manson yang blak-blakan sangat menyegarkan di tengah lautan buku motivasi klise. Bukan bersikap acuh tak acuh, tapi berani memilih nilai hidup yang patut diperjuangkan.',
            ],

            // Jujutsu Kaisen
            [
                'book' => 'Jujutsu Kaisen (Vol. 1)',
                'email' => 'rian.hidayat@rebook.com',
                'rating' => 5.0,
                'days_ago' => 230,
                'content' => 'Volume pembuka yang sangat solid! Konsep energi kutukan dan desain monster kutukan Gege Akutami memiliki nuansa dark fantasy supernatural yang sangat memikat. Karakter Gojo Satoru langsung mencuri perhatian.',
            ],
            [
                'book' => 'Jujutsu Kaisen (Vol. 1)',
                'email' => 'bagas.wicaksono@rebook.com',
                'rating' => 4.5,
                'days_ago' => 95,
                'content' => 'Aksinya dinamis dan paneling pertarungannya sangat rapi. Sukuna sebagai entitas di dalam tubuh Itadori membuat tensi cerita selalu menegangkan.',
            ],

            // Death Note
            [
                'book' => 'Death Note (Vol. 1)',
                'email' => 'rian.hidayat@rebook.com',
                'rating' => 5.0,
                'days_ago' => 350,
                'content' => 'Duel psikologis paling legendaris dalam sejarah manga. Pertarungan pikiran antara Light dan L di volume 1 ini adalah penulisan skenario thriller paling cerdas yang pernah ada.',
            ],
            [
                'book' => 'Death Note (Vol. 1)',
                'email' => 'gilang.permana@rebook.com',
                'rating' => 5.0,
                'days_ago' => 140,
                'content' => 'Ilustrasi Takeshi Obata yang sangat detail dipadu dengan naskah Tsugumi Ohba yang padat teka-teki. Setiap bab berakhir dengan cliffhanger yang bikin tidak bisa berhenti membaca.',
            ],
        ];

        $createdReviews = [];
        foreach ($organicReviews as $revData) {
            $book = $bookMap[$revData['book']] ?? null;
            $user = $userMap[$revData['email']] ?? null;

            if ($book && $user) {
                $createdAt = $baseDate->copy()->subDays($revData['days_ago'])->subHours(rand(1, 23))->subMinutes(rand(1, 59));

                $review = Review::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'book_id' => $book->id,
                    ],
                    [
                        'rating' => $revData['rating'],
                        'content' => $revData['content'],
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]
                );
                $createdReviews[] = $review;
            }
        }

        // -----------------------------------------------------------------
        // 7. LIKES / REAKSI MEMBANTU PADA ULASAN DENGAN TIMESTAMPS REALISTIS
        // -----------------------------------------------------------------
        foreach ($createdReviews as $rev) {
            $likerCount = rand(3, min(9, count($allMembers)));
            $likers = $allMembers->where('id', '!=', $rev->user_id)->random($likerCount);

            foreach ($likers as $liker) {
                $likeDate = $rev->created_at->copy()->addHours(rand(1, 72));
                if ($likeDate->isFuture()) {
                    $likeDate = now()->subMinutes(rand(5, 60));
                }

                DB::table('review_likes')->updateOrInsert(
                    ['user_id' => $liker->id, 'review_id' => $rev->id],
                    [
                        'created_at' => $likeDate,
                        'updated_at' => $likeDate,
                    ]
                );
            }
        }

        // -----------------------------------------------------------------
        // 8. KOMENTAR DISKUSI REALISTIS ANTAR MEMBER DENGAN TIMESTAMPS TERTATA
        // -----------------------------------------------------------------
        $commentPool = [
            'Setuju banget sama poin ini! Bener-bener membuka perspektif baru.',
            'Betul, cara penulis menyusun alur ceritanya memang gak ada tandingannya.',
            'Ulasan yang sangat menyentuh. Memang bagian itu bikin nangis sesenggukan pas baca.',
            'Endingnya bener-bener plot twist gila sih, wajib dibaca ulang biar ngeh petunjuk-petunjuk kecilnya!',
            'Saya terapkan tips di buku ini dan beneran kerasa perubahannya dalam produktivitas harian.',
            'Terima kasih atas ulasannya! Jadi makin mantap buat beli buku fisiknya akhir pekan ini.',
            'Karakter utamanya emang badass banget, ga sabar nunggu adaptasi lanjutannya!',
            'Analisis yang mendalam, senang melihat pembaca yang mengapresiasi karya sastra berbobot seperti ini.',
            'Bener banget, salah satu buku terbaik yang pernah saya baca tahun ini.',
            'Kutipan yang kamu sebutkan itu juga jadi favorit saya!',
        ];

        foreach (array_slice($createdReviews, 0, 25) as $rev) {
            $numComments = rand(1, 3);
            $commenters = $allMembers->where('id', '!=', $rev->user_id)->random(min($numComments, count($allMembers) - 1));

            $stepHours = 3;
            foreach ($commenters as $commenter) {
                $commentDate = $rev->created_at->copy()->addHours($stepHours + rand(1, 12));
                if ($commentDate->isFuture()) {
                    $commentDate = now()->subMinutes(rand(10, 120));
                }
                $stepHours += 8;

                $commentText = $commentPool[array_rand($commentPool)];

                Comment::create([
                    'user_id' => $commenter->id,
                    'review_id' => $rev->id,
                    'comment' => $commentText,
                    'created_at' => $commentDate,
                    'updated_at' => $commentDate,
                ]);
            }
        }

        // -----------------------------------------------------------------
        // 9. JARINGAN PERTEMANAN / FOLLOW ANTAR MEMBER
        // -----------------------------------------------------------------
        foreach ($allMembers as $m) {
            $followCount = rand(4, min(7, count($allMembers) - 1));
            $toFollow = $allMembers->where('id', '!=', $m->id)->random($followCount)->pluck('id')->toArray();
            $m->following()->syncWithoutDetaching($toFollow);
        }

        $this->command->info('Organic Community Seeder berhasil dijalankan!');
        $this->command->info('- Total Buku: '.Book::count());
        $this->command->info('- Total Member: '.User::where('role', 'member')->count());
        $this->command->info('- Total Ulasan: '.Review::count());
        $this->command->info('- Total Suka Ulasan: '.DB::table('review_likes')->count());
        $this->command->info('- Total Komentar: '.Comment::count());
        $this->command->info('- Total Rak Bacaan: '.ReadingShelf::count());
        $this->command->info('- Total Follow: '.DB::table('user_follows')->count());
    }
}
