@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="rebook-pagination">
        {{-- Info Jumlah Data --}}
        <div class="pagination-info">
            Menampilkan
            @if ($paginator->firstItem())
                <strong>{{ $paginator->firstItem() }}</strong> - <strong>{{ $paginator->lastItem() }}</strong>
            @else
                <strong>{{ $paginator->count() }}</strong>
            @endif
            dari <strong>{{ $paginator->total() }}</strong> data
        </div>

        {{-- Navigasi Tombol Angka & Panah --}}
        <div class="pagination-links">
            {{-- Tombol Halaman Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="page-link disabled" aria-disabled="true" aria-label="Halaman Sebelumnya">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-link" aria-label="Halaman Sebelumnya">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            @endif

            {{-- Elemen Angka Paginasi --}}
            @foreach ($elements as $element)
                {{-- Separator Titik Tiga (...) --}}
                @if (is_string($element))
                    <span class="page-link disabled" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Nomor Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-link active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Halaman Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-link" aria-label="Halaman Berikutnya">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            @else
                <span class="page-link disabled" aria-disabled="true" aria-label="Halaman Berikutnya">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
