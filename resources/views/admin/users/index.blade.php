@extends('layouts.admin')

@section('title', 'Kelola Member - Admin Re:Book')

@section('admin_content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
  <div>
    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
      <i class="fa-solid fa-users" style="color: #3fb950;"></i> Kelola & Moderasi User Member
    </h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">
      Admin bisa mencari, memantau aktivitas, serta memblokir atau mengaktifkan kembali akun member.
    </p>
  </div>
  <a href="{{ route('admin.exports.users') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-file-csv" style="color: #3fb950;"></i> Export CSV Member
  </a>
</div>

{{-- Bar Pencarian & Filter Status Member --}}
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; margin-bottom: 20px;">
  <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
    {{-- Input Search --}}
    <div style="flex: 1; min-width: 240px;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email member..."
             style="width: 100%; padding: 8px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.88rem; outline: none;">
    </div>

    {{-- Filter Status --}}
    <div style="min-width: 160px;">
      <select name="status" style="width: 100%; padding: 8px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.88rem; outline: none; cursor: pointer;">
        <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
        <option value="Aktif" {{ request('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="Diblokir" {{ request('status') === 'Diblokir' ? 'selected' : '' }}>Diblokir</option>
      </select>
    </div>

    <button type="submit" style="background: var(--admin-accent); color: white; padding: 8px 16px; border-radius: 6px; border: none; font-size: 0.88rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
      <i class="fa-solid fa-filter"></i> Saring
    </button>

    @if(request()->anyFilled(['search', 'status']) && (request('status') !== 'all' || request('search') !== ''))
      <a href="{{ route('admin.users.index') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-muted); padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 0.85rem;">
        Reset
      </a>
    @endif
  </form>
</div>

<!-- Tabel Member -->
<table class="custom-table" style="margin-top: 0;">
  <thead>
    <tr>
      <th style="width: 60px;">ID</th>
      <th>Member</th>
      <th>Aktivitas Komunitas</th>
      <th>Status</th>
      <th style="width: 200px;">Aksi Admin</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($members as $u)
      <tr>
        <td style="color: var(--text-muted); font-size: 0.85rem;">#{{ $u->id }}</td>
        <td>
          <div style="display: flex; align-items: center; gap: 12px;">
            <x-user-avatar :user="$u" size="sm" />
            <div>
              <div style="font-weight: 700; color: var(--text-main); margin-bottom: 2px;">{{ $u->name }}</div>
              <div style="color: var(--text-muted); font-size: 0.8rem;">{{ $u->email }}</div>
            </div>
          </div>
        </td>
        <td>
          <div style="font-size: 0.85rem; color: var(--text-main); margin-bottom: 2px;">
            <i class="fa-solid fa-pen-nib" style="color: #3fb950; font-size: 0.75rem;"></i> <strong>{{ $u->reviews_count }}</strong> ulasan
          </div>
          <div style="font-size: 0.78rem; color: var(--text-muted);">
            <i class="fa-solid fa-book-bookmark" style="color: #58a6ff; font-size: 0.75rem;"></i> {{ $u->reading_shelves_count }} buku di rak
          </div>
        </td>
        <td>
          <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; background: {{ $u->status === 'Aktif' ? 'rgba(35, 134, 54, 0.18)' : 'rgba(218, 54, 51, 0.18)' }}; color: {{ $u->status === 'Aktif' ? '#3fb950' : '#f85149' }}; border: 1px solid {{ $u->status === 'Aktif' ? '#238636' : '#da3633' }};">
            <i class="fa-solid {{ $u->status === 'Aktif' ? 'fa-check' : 'fa-ban' }}" style="font-size: 0.7rem;"></i>
            {{ $u->status }}
          </span>
        </td>
        <td>
          <div style="display: flex; align-items: center; gap: 8px;">
            <form action="{{ route('admin.users.toggle-block', $u->id) }}" method="POST" style="display: inline;"
                  onsubmit="return confirm('Apakah Anda yakin ingin {{ $u->status === 'Aktif' ? 'memblokir' : 'mengaktifkan kembali' }} akun {{ $u->name }}?');">
              @csrf
              @method('PATCH')
              <button type="submit" style="padding: 6px 12px; border-radius: 4px; border: none; font-size: 0.8rem; font-weight: 600; cursor: pointer; background: {{ $u->status === 'Aktif' ? 'var(--admin-danger)' : 'var(--admin-accent)' }}; color: #fff;">
                <i class="fa-solid {{ $u->status === 'Aktif' ? 'fa-ban' : 'fa-check' }}"></i>
                {{ $u->status === 'Aktif' ? 'Blokir' : 'Aktifkan' }}
              </button>
            </form>

            <a href="{{ route('users.profile', $u->id) }}" target="_blank" style="padding: 6px 10px; background: #21262d; border: 1px solid var(--border-color); color: #58a6ff; border-radius: 4px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
              Profil <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.68rem;"></i>
            </a>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
          Tidak ada member yang cocok dengan filter pencarian.
        </td>
      </tr>
    @endforelse
  </tbody>
</table>

{{-- Pagination Links --}}
<div style="margin-top: 20px;">
  {{ $members->links() }}
</div>
@endsection
