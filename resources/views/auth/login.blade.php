@extends('layouts.app')

@section('content')
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 30px; max-width: 380px; margin: 40px auto;">
  <h3 style="text-align: center; margin-bottom: 20px;">Sign in to Re:Book</h3>

  @if($errors->any())
    <div style="background: var(--admin-danger); color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 15px;">
      {{ $errors->first() }}
    </div>
  @endif

  <form action="{{ route('login') }}" method="POST">
    @csrf
    <div style="margin-bottom: 15px;">
      <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 5px;">Email Address</label>
      <input type="email" name="email" required style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
    </div>

    <div style="margin-bottom: 20px;">
      <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 5px;">Password</label>
      <input type="password" name="password" required style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
    </div>

    <button type="submit" style="width: 100%; padding: 10px; background: var(--member-accent); border: none; color: #fff; font-weight: 600; border-radius: 6px; cursor: pointer;">Sign in</button>
  </form>
</div>
@endsection