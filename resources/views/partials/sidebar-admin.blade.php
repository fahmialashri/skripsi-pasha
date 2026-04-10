@php
  // 1. Definisi Menu Navigasi dalam bentuk Array
  // Memudahkan menambah/mengurangi menu tanpa mengubah struktur HTML di bawah
  $nav = [
    [
      'label' => 'Dashboard',
      'href'  => route('admin.dashboard'),
      'icon'  => 'home',
      'active_when' => ['admin.dashboard'], // Nama route yang memicu status 'active'
    ],
    [
      'label' => 'Pengajuan Masuk',
      'href'  => route('admin.proposals.index'),
      'icon'  => 'file',
      'active_when' => ['admin.proposals.*'], // Menggunakan wildcard '*' agar sub-halaman tetap aktif
    ],
    [
      'label' => 'Kelola Dosen',
      'href'  => route('admin.dosens.index'),
      'icon'  => 'users',
      'active_when' => ['admin.dosens.*'],
    ],
    [
      'label' => 'Pengumuman',
      'href'  => route('admin.announcements.index'),
      'icon'  => 'megaphone',
      'active_when' => ['admin.announcements.*'],
    ],
  ];

  // 2. Fungsi Helper untuk mengecek apakah halaman sedang aktif
  // Mendukung pengecekan berdasarkan pola URL atau Nama Route
  $isActiveFor = function(array $patterns) {
    foreach ($patterns as $p) {
      if (str_contains($p, '/')) {
        // Jika pola mengandung '/', cek berdasarkan URL path
        if (request()->is($p)) return true;
      } else {
        // Jika tidak, cek berdasarkan Nama Route Laravel
        if (request()->routeIs($p)) return true;
      }
    }
    return false;
  };
@endphp

<aside class="w-[260px] bg-white border-r border-slate-200">
  
  <div class="px-6 pt-6 pb-4">
    <div class="flex items-center gap-3">
      <div class="h-10 w-10 rounded-xl bg-slate-900/5 flex items-center justify-center">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-slate-700">
          <path d="M4 7.5C4 6.12 5.12 5 6.5 5h11C18.88 5 20 6.12 20 7.5v9c0 1.38-1.12 2.5-2.5 2.5h-11C5.12 19 4 17.88 4 16.5v-9Z" stroke="currentColor" stroke-width="1.6"/>
          <path d="M8 9h8M8 12h8M8 15h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
      </div>
      <div>
        <div class="text-sm font-semibold leading-4">Portal Skripsi</div>
        <div class="text-[11px] text-slate-400 mt-0.5">KAPRODI PANEL</div>
      </div>
    </div>
  </div>

  <nav class="px-4 pt-2">
    <div class="space-y-1.5">
      @foreach($nav as $item)
        @php
          // Tentukan apakah menu ini sedang aktif atau idle
          $isActive = $isActiveFor($item['active_when']);
          
          // Class CSS dasar untuk link
          $base   = "flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition";
          // Class khusus saat menu dipilih
          $active = "bg-slate-100 text-slate-900 font-semibold";
          // Class saat menu tidak dipilih (default/hover)
          $idle   = "text-slate-600 hover:bg-slate-50 hover:text-slate-800";
        @endphp

        <a href="{{ $item['href'] }}" class="{{ $base }} {{ $isActive ? $active : $idle }}">
          
          @if($item['icon'] === 'home')
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
              <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1v-9.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
            </svg>
          @elseif($item['icon'] === 'file')
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
              <path d="M7 3h7l3 3v15a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="1.7"/>
              <path d="M14 3v4h4" stroke="currentColor" stroke-width="1.7"/>
              <path d="M8.5 12h7M8.5 15h7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
            </svg>
          @elseif($item['icon'] === 'users')
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
              <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.7"/>
              <path d="M22 21v-2a3.5 3.5 0 0 0-2.5-3.35" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
              <path d="M16.5 3.65a4 4 0 0 1 0 7.7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
            </svg>
          @else {{-- Ikon Megaphone untuk Pengumuman --}}
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
              <path d="M3 11v2a2 2 0 0 0 2 2h1l5 3V6L6 9H5a2 2 0 0 0-2 2Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
              <path d="M11 6l8-3v18l-8-3V6Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
              <path d="M6 15v4a2 2 0 0 0 2 2h1" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
            </svg>
          @endif

          <span>{{ $item['label'] }}</span>
        </a>
      @endforeach
    </div>

    <div class="mt-10 px-2">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex items-center gap-2 text-sm text-rose-500 hover:text-rose-600 transition duration-200">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
            <path d="M10 17l-1 0a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4h1" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
            <path d="M14 7l5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M19 12H10" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
          </svg>
          <span>Keluar</span>
        </button>
      </form>
    </div>
  </nav>
</aside>