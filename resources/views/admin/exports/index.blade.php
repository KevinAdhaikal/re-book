@extends('layouts.admin')

@section('admin_content')
<div style="margin-bottom: 25px;">
  <h2 style="font-size: 1.6rem; color: var(--text-main); margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
    <i class="fa-solid fa-file-arrow-down" style="color: #3fb950;"></i> Export Data Re:Book
  </h2>
  <p style="color: var(--text-muted); font-size: 0.9rem;">
    Unduh data master sistem ke format CSV yang kompatibel dengan Microsoft Excel, Google Sheets, dan Numbers.
  </p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
  {{-- Card Export Buku --}}
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 25px; display: flex; flex-direction: column; justify-content: space-between;">
    <div>
      <div style="width: 45px; height: 45px; border-radius: 8px; background: rgba(31, 111, 235, 0.15); border: 1px solid var(--member-accent); color: #58a6ff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 16px;">
        <i class="fa-solid fa-book"></i>
      </div>
      <h3 style="color: var(--text-main); font-size: 1.15rem; margin-bottom: 8px;">Katalog Buku</h3>
      <p style="color: var(--text-muted); font-size: 0.85rem; line-height: 1.5; margin-bottom: 15px;">
        Mencakup ID, Judul, Penulis, Genre, Rata-rata Rating, Jumlah Ulasan, dan Jumlah Like buku.
      </p>
      <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px;">
        Total Data: <strong style="color: var(--text-main);">{{ $totalBooks }} Buku</strong>
      </div>
    </div>
    <a href="{{ route('admin.exports.books') }}"
       style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s;"
       onmouseover="this.style.background='#1f6feb'; this.style.borderColor='#1f6feb';"
       onmouseout="this.style.background='#21262d'; this.style.borderColor='var(--border-color)';">
      <i class="fa-solid fa-download"></i> Unduh CSV Buku
    </a>
  </div>

  {{-- Card Export Member --}}
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 25px; display: flex; flex-direction: column; justify-content: space-between;">
    <div>
      <div style="width: 45px; height: 45px; border-radius: 8px; background: rgba(63, 185, 80, 0.15); border: 1px solid var(--admin-accent); color: #3fb950; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 16px;">
        <i class="fa-solid fa-users"></i>
      </div>
      <h3 style="color: var(--text-main); font-size: 1.15rem; margin-bottom: 8px;">Daftar Member</h3>
      <p style="color: var(--text-muted); font-size: 0.85rem; line-height: 1.5; margin-bottom: 15px;">
        Mencakup ID, Nama, Email, Status Akun, Bio, Genre Favorit, Total Ulasan, dan Jumlah Pengikut.
      </p>
      <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px;">
        Total Data: <strong style="color: var(--text-main);">{{ $totalUsers }} Member</strong>
      </div>
    </div>
    <a href="{{ route('admin.exports.users') }}"
       style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s;"
       onmouseover="this.style.background='#238636'; this.style.borderColor='#238636';"
       onmouseout="this.style.background='#21262d'; this.style.borderColor='var(--border-color)';">
      <i class="fa-solid fa-download"></i> Unduh CSV Member
    </a>
  </div>

  {{-- Card Export Ulasan --}}
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 25px; display: flex; flex-direction: column; justify-content: space-between;">
    <div>
      <div style="width: 45px; height: 45px; border-radius: 8px; background: rgba(227, 179, 65, 0.15); border: 1px solid var(--star-rating); color: #e3b341; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 16px;">
        <i class="fa-solid fa-comments"></i>
      </div>
      <h3 style="color: var(--text-main); font-size: 1.15rem; margin-bottom: 8px;">Semua Ulasan</h3>
      <p style="color: var(--text-muted); font-size: 0.85rem; line-height: 1.5; margin-bottom: 15px;">
        Mencakup ID Ulasan, Judul Buku, Member Penulis, Rating, Teks Ulasan, Like, dan Komentar.
      </p>
      <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px;">
        Total Data: <strong style="color: var(--text-main);">{{ $totalReviews }} Ulasan</strong>
      </div>
    </div>
    <a href="{{ route('admin.exports.reviews') }}"
       style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s;"
       onmouseover="this.style.background='#d29922'; this.style.borderColor='#d29922';"
       onmouseout="this.style.background='#21262d'; this.style.borderColor='var(--border-color)';">
      <i class="fa-solid fa-download"></i> Unduh CSV Ulasan
    </a>
  </div>
</div>
@endsection
