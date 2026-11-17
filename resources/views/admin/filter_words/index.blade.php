@extends('layouts.admin')

@section('title', 'Filter Kata Kasar - Admin Re:Book')

@section('admin_content')
<div style="margin-bottom: 20px;">
  <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
    <i class="fa-solid fa-filter" style="color: var(--favorite-coral);"></i> Filter Kata Kasar (Sensitivitas Teks)
  </h2>
  <p style="color: var(--text-muted); font-size: 0.9rem;">
    Kata-kata dalam daftar ini akan otomatis disensor atau disaring saat member menulis ulasan.
  </p>
</div>

<form action="{{ route('admin.words.store') }}" method="POST" style="margin: 20px 0; display: flex; gap: 10px; max-width: 440px;">
  @csrf
  <input type="text" name="word" placeholder="Tambah kata kasar baru..." required style="flex: 1; padding: 9px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.88rem;">
  <button type="submit" style="background: var(--admin-accent); color: #fff; border: none; padding: 0 16px; border-radius: 6px; font-weight: 600; font-size: 0.88rem; cursor: pointer; display: flex; align-items: center; gap: 6px;">
    <i class="fa-solid fa-plus"></i> Tambah
  </button>
</form>

<table class="custom-table" style="max-width: 550px; margin-top: 0;">
  <thead>
    <tr>
      <th style="width: 60px;">No</th>
      <th>Kata Terblokir</th>
      <th style="width: 100px;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($badWords as $idx => $w)
      <tr>
        <td style="color: var(--text-muted); font-size: 0.85rem;">{{ $idx + 1 }}</td>
        <td>
          <code style="background: #21262d; border: 1px solid var(--border-color); color: var(--favorite-coral); font-size: 0.9rem; padding: 3px 8px; border-radius: 4px;">
            {{ $w->word }}
          </code>
        </td>
        <td>
          <form action="{{ route('admin.words.destroy', $w->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus kata \'{{ $w->word }}\' dari filter?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: none; border: none; color: var(--admin-danger); cursor: pointer; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
              <i class="fa-solid fa-trash"></i> Hapus
            </button>
          </form>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 25px;">
          Belum ada kata kasar dalam daftar filter.
        </td>
      </tr>
    @endforelse
  </tbody>
</table>
@endsection
