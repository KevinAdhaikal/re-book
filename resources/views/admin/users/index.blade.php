@extends('layouts.admin')

@section('admin_content')
  <h2>Kelola & Moderasi User Member</h2>
  <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;">Admin bisa memblokir atau mengaktifkan kembali akun member.</p>
  
  <table class="custom-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama Member</th>
        <th>Email</th>
        <th>Status</th>
        <th>Aksi Admin</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($members as $u)
        <tr>
          <td>#{{ $u->id }}</td>
          <td><strong>{{ $u->name }}</strong></td>
          <td>{{ $u->email }}</td>
          <td>
            <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; background: {{ $u->status === 'Aktif' ? 'rgba(35, 134, 54, 0.2)' : 'rgba(218, 54, 51, 0.2)' }}; color: {{ $u->status === 'Aktif' ? '#3fb950' : '#f85149' }}; border: 1px solid {{ $u->status === 'Aktif' ? '#238636' : '#da3633' }};">
              {{ $u->status }}
            </span>
          </td>
          <td>
            <form action="{{ route('admin.users.toggle-block', $u->id) }}" method="POST" style="display: inline;">
              @csrf
              @method('PATCH')
              <button type="submit" style="padding: 6px 12px; border-radius: 4px; border: none; font-size: 0.8rem; font-weight: 600; cursor: pointer; background: {{ $u->status === 'Aktif' ? 'var(--admin-danger)' : 'var(--admin-accent)' }}; color: #fff;">
                {{ $u->status === 'Aktif' ? 'Blokir Akun' : 'Aktifkan Akun' }}
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection