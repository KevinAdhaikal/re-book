@extends('layouts.admin')

@section('title', 'Filter Kata Kasar - Admin Re:Book')

@section('admin_content')
<div style="margin-bottom: 25px;">
  <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
    <div>
      <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-filter" style="color: var(--favorite-coral);"></i> Filter Kata Kasar & Moderasi Konteks
      </h2>
      <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">
        Kelola kamus kata terlarang serta uji coba sensor kontekstual (membedakan satwa/literatur vs umpatan/makian).
      </p>
    </div>
    <span style="background: rgba(35, 134, 54, 0.15); border: 1px solid var(--admin-accent); color: #3fb950; font-size: 0.82rem; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
      Total: {{ $badWords->count() }} Kata Terdaftar
    </span>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px;">
  
  {{-- ================= 1. SIMULATOR PENGUJIAN SENSOR LIVE ================= --}}
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 22px; display: flex; flex-direction: column;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
      <h4 style="color: white; font-size: 0.95rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-flask" style="color: #58a6ff;"></i> Simulator Pengujian Sensor Teks
      </h4>
      <span id="simulatorStatusBadge" style="font-size: 0.72rem; font-weight: 600; padding: 2px 8px; border-radius: 12px; background: #21262d; color: var(--text-muted);">
        Siap Uji
      </span>
    </div>

    <p style="color: var(--text-muted); font-size: 0.8rem; margin: 0 0 12px 0;">
      Ketik kalimat apa saja untuk melihat secara langsung bagaimana engine menyensor teks berdasarkan konteks.
    </p>

    {{-- Tombol Cepat Contoh Kasus --}}
    <div style="display: flex; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
      <button type="button" onclick="setSimulatorSample(1)" style="background: #21262d; border: 1px solid var(--border-color); color: #58a6ff; font-size: 0.75rem; font-weight: 600; padding: 4px 10px; border-radius: 6px; cursor: pointer;">
        Contoh: Satwa/Fiksi (Aman)
      </button>
      <button type="button" onclick="setSimulatorSample(2)" style="background: #21262d; border: 1px solid var(--border-color); color: #f85149; font-size: 0.75rem; font-weight: 600; padding: 4px 10px; border-radius: 6px; cursor: pointer;">
        Contoh: Umpatan (Disensor)
      </button>
    </div>

    <textarea id="simulatorInput" rows="3" placeholder="Ketik atau tempel teks di sini untuk menguji..."
              style="width: 100%; padding: 10px 12px; background: #0d1117; border: 1px solid var(--border-color); color: white; border-radius: 6px; font-size: 0.88rem; outline: none; resize: vertical; margin-bottom: 12px;"
              oninput="handleSimulatorInput(this.value)"></textarea>

    {{-- Kotak Hasil Sensor Live --}}
    <div>
      <div style="font-size: 0.78rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
        Hasil Sensor Real-time:
      </div>
      <div id="simulatorOutput" style="background: #0d1117; border: 1px solid var(--border-color); border-radius: 6px; padding: 12px 14px; color: var(--text-main); font-size: 0.9rem; min-height: 48px; line-height: 1.5; font-family: var(--font-sans);">
        <em style="color: var(--text-muted); font-size: 0.82rem;">Ketik teks pada kotak di atas untuk melihat simulasi hasil sensor...</em>
      </div>
    </div>
  </div>

  {{-- ================= 2. FORM TAMBAH KATA (BATCH SUPPORT) ================= --}}
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 22px; display: flex; flex-direction: column;">
    <h4 style="color: white; font-size: 0.95rem; font-weight: 700; margin: 0 0 6px 0; display: flex; align-items: center; gap: 8px;">
      <i class="fa-solid fa-plus" style="color: var(--admin-accent);"></i> Tambah Kata Kasar Terblokir
    </h4>
    <p style="color: var(--text-muted); font-size: 0.8rem; margin: 0 0 14px 0;">
      Anda dapat memasukkan satu kata atau <strong>banyak kata sekaligus</strong> dengan memisahkannya menggunakan tanda koma (<code>,</code>) atau baris baru.
    </p>

    <form action="{{ route('admin.words.store') }}" method="POST" style="display: flex; flex-direction: column; flex: 1;">
      @csrf
      <textarea name="words" rows="4" required placeholder="Contoh: bangsat, bajingan, goblok, tolol, kampret"
                style="width: 100%; padding: 10px 12px; background: #0d1117; border: 1px solid var(--border-color); color: white; border-radius: 6px; font-size: 0.88rem; outline: none; resize: vertical; margin-bottom: 12px; flex: 1;"></textarea>

      <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 0.75rem; color: var(--text-muted);">Format: Otomatis di-lowercase & di-trim</span>
        <button type="submit" style="background: var(--admin-accent); color: #fff; border: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
          <i class="fa-solid fa-circle-plus"></i> Tambah ke Kamus
        </button>
      </div>
    </form>
  </div>

</div>

{{-- ================= 3. TABEL DAFTAR KATA TERBLOKIR ================= --}}
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 22px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h3 style="color: white; font-size: 1.1rem; font-weight: 700; margin: 0 0 4px 0;">
        Daftar Kata Terdaftar di Kamus Sensor
      </h3>
      <p style="color: var(--text-muted); font-size: 0.82rem; margin: 0;">
        Kata-kata di bawah ini aktif disaring pada Ulasan, Komentar, dan Bio profil member.
      </p>
    </div>

    {{-- Pencarian Cepat di Tabel --}}
    <div style="position: relative; width: 260px;">
      <input type="text" id="tableFilterInput" placeholder="Cari kata dalam tabel..." oninput="filterWordTable(this.value)"
             style="width: 100%; padding: 7px 12px 7px 32px; background: #0d1117; border: 1px solid var(--border-color); color: white; border-radius: 6px; font-size: 0.85rem; outline: none;">
      <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 9px; color: var(--text-muted); font-size: 0.8rem;"></i>
    </div>
  </div>

  @php
    $ambiguousList = ['anjing', 'babi', 'monyet', 'kampret', 'buaya', 'ular', 'setan', 'iblis'];
  @endphp

  <table class="custom-table" style="margin-top: 0;">
    <thead>
      <tr>
        <th style="width: 60px;">No</th>
        <th>Kata Terblokir</th>
        <th style="width: 220px;">Klasifikasi Sensor</th>
        <th style="width: 160px;">Waktu Ditambahkan</th>
        <th style="width: 100px; text-align: center;">Aksi</th>
      </tr>
    </thead>
    <tbody id="badWordsTableBody">
      @forelse ($badWords as $idx => $w)
        @php
          $isAmbiguous = in_array(strtolower($w->word), $ambiguousList);
        @endphp
        <tr class="bad-word-row" data-word="{{ strtolower($w->word) }}">
          <td style="color: var(--text-muted); font-size: 0.85rem;">{{ $idx + 1 }}</td>
          <td>
            <code style="background: #21262d; border: 1px solid var(--border-color); color: var(--favorite-coral); font-size: 0.92rem; font-weight: 600; padding: 3px 10px; border-radius: 5px;">
              {{ $w->word }}
            </code>
          </td>
          <td>
            @if($isAmbiguous)
              <span style="background: rgba(31, 111, 235, 0.15); border: 1px solid #1f6feb; color: #58a6ff; font-size: 0.75rem; font-weight: 600; padding: 3px 9px; border-radius: 12px; display: inline-flex; align-items: center; gap: 5px;" title="Disensor hanya jika terindikasi sebagai umpatan, diizinkan jika konteks satwa/cerita">
                <i class="fa-solid fa-shield-cat"></i> Kontekstual (Satwa/Cerita)
              </span>
            @else
              <span style="background: rgba(218, 54, 51, 0.15); border: 1px solid var(--admin-danger); color: #f85149; font-size: 0.75rem; font-weight: 600; padding: 3px 9px; border-radius: 12px; display: inline-flex; align-items: center; gap: 5px;" title="Selalu disensor menjadi bintang">
                <i class="fa-solid fa-ban"></i> Selalu Disensor
              </span>
            @endif
          </td>
          <td style="color: var(--text-muted); font-size: 0.82rem;">
            {{ $w->created_at ? $w->created_at->format('d M Y, H:i') : '-' }}
          </td>
          <td style="text-align: center;">
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
          <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
            Belum ada kata terblokir dalam daftar filter.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

@push('scripts')
<script>
  // ================= SIMULATOR LIVE PREVIEW SCRIPT =================
  let simulatorTimer = null;
  const testUrl = "{{ route('admin.words.test') }}";
  const csrfToken = "{{ csrf_token() }}";

  function setSimulatorSample(type) {
    const input = document.getElementById('simulatorInput');
    if (!input) return;

    if (type === 1) {
      input.value = "Novel ini bercerita tentang seekor anjing setia dan babi hutan di lereng gunung.";
    } else {
      input.value = "Dasar anjing lu reviewernya, bukunya jelek bgt!";
    }
    handleSimulatorInput(input.value);
  }

  function handleSimulatorInput(text) {
    clearTimeout(simulatorTimer);
    const outputBox = document.getElementById('simulatorOutput');
    const badge = document.getElementById('simulatorStatusBadge');

    if (!text || text.trim() === '') {
      outputBox.innerHTML = '<em style="color: var(--text-muted); font-size: 0.82rem;">Ketik teks pada kotak di atas untuk melihat simulasi hasil sensor...</em>';
      badge.textContent = 'Siap Uji';
      badge.style.background = '#21262d';
      badge.style.color = 'var(--text-muted)';
      return;
    }

    badge.textContent = 'Memeriksa...';
    badge.style.background = 'rgba(227, 179, 65, 0.15)';
    badge.style.color = '#e3b341';

    simulatorTimer = setTimeout(() => {
      fetch(testUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ text: text })
      })
      .then(res => res.json())
      .then(data => {
        if (data.has_censorship) {
          // Highlight kata yang disensor dengan warna coral/bintang
          const highlighted = escapeHtml(data.filtered).replace(/(\*+)/g, '<span style="color: #f85149; font-weight: 700; background: rgba(218,54,51,0.2); padding: 1px 4px; border-radius: 3px;">$1</span>');
          outputBox.innerHTML = highlighted;
          badge.textContent = 'Terdeteksi & Disensor (****)';
          badge.style.background = 'rgba(218, 54, 51, 0.2)';
          badge.style.color = '#f85149';
        } else {
          outputBox.innerHTML = `<span style="color: #3fb950;"><i class="fa-solid fa-check"></i> ${escapeHtml(data.filtered)}</span>`;
          badge.textContent = 'Aman (Konteks Satwa/Fiksi)';
          badge.style.background = 'rgba(35, 134, 54, 0.2)';
          badge.style.color = '#3fb950';
        }
      })
      .catch(err => {
        badge.textContent = 'Gagal memeriksa';
        badge.style.background = 'rgba(218, 54, 51, 0.2)';
        badge.style.color = '#f85149';
      });
    }, 250);
  }

  function escapeHtml(string) {
    const entityMap = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
      '/': '&#x2F;'
    };
    return String(string).replace(/[&<>"'\/]/g, s => entityMap[s]);
  }

  // ================= PENCARIAN CEPAT TABEL =================
  function filterWordTable(query) {
    const q = query.toLowerCase().trim();
    const rows = document.querySelectorAll('.bad-word-row');
    rows.forEach(row => {
      const word = row.getAttribute('data-word');
      if (!q || (word && word.includes(q))) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }
</script>
@endpush
