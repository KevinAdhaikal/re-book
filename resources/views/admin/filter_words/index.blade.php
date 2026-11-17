@extends('layouts.admin')

@section('admin_content')
  <h2>Filter Kata Kasar (Sensitivitas Teks)</h2>
  
  <form action="{{ route('admin.words.store') }}" method="POST" style="margin: 20px 0; display: flex; gap: 10px; max-width: 400px;">
    @csrf
    <input type="text" name="word" placeholder="Tambah kata kasar baru..." required style="flex: 1; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
    <button type="submit" style="background: var(--admin-accent); color: #fff; border: none; padding: 0 15px; border-radius: 6px; font-weight: 600; cursor: pointer;">Tambah</button>
  </form>

  <table class="custom-table" style="max-width: 500px;">
    <thead>
      <tr>
        <th>No</th>
        <th>Kata Terblokir</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($badWords as $idx => $w)
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td><code style="color: var(--favorite-coral); font-size: 0.95rem;">{{ $w->word }}</code></td>
          <td>
            <form action="{{ route('admin.words.destroy', $w->id) }}" method="POST" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="submit" style="background: none; border: none; color: var(--admin-danger); cursor: pointer;"><i class="fa-solid fa-trash"></i> Hapus</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection