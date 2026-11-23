<div class="book-grid" id="book-catalog-grid">
  @php
    if (!isset($userLikedIds)) {
      $userLikedIds = auth()->check() ? \Illuminate\Support\Facades\DB::table('book_likes')->where('user_id', auth()->id())->pluck('book_id')->toArray() : [];
    }
  @endphp

  @forelse ($books as $b)
    @php
      $isLiked = in_array($b->id, $userLikedIds);
    @endphp
    <x-book-card :book="$b" :isLiked="$isLiked" />
  @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: var(--bg-card); border-radius: 8px; border: 1px solid var(--border-color);">
      <i class="fa-solid fa-magnifying-glass" style="font-size: 2rem; color: #30363d; margin-bottom: 10px;"></i>
      <h3 style="color: var(--text-main); font-size: 1rem; margin-bottom: 6px;">Buku Tidak Ditemukan</h3>
      <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 16px;">
        Tidak ada buku yang cocok dengan filter atau kata kunci yang dipilih.
      </p>
      <a href="{{ url('/') }}" class="genre-filter-pill" data-genre="Semua" style="background: var(--member-accent); color: white; padding: 7px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-block;">
        Kembali ke Semua Koleksi
      </a>
    </div>
  @endforelse
</div>

{{-- Pagination Links --}}
@if ($books->hasPages())
  <div class="catalog-pagination-wrap" style="margin-top: 24px; display: flex; justify-content: center;">
    {{ $books->links() }}
  </div>
@endif
