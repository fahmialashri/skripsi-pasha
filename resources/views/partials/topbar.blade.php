@php
  // title kiri (default)
  $pageTitle = $pageTitle ?? 'Dashboard Utama';

  // mode topbar: 'default' atau 'profile'
  $topbarVariant = $topbarVariant ?? 'default';

  // active tab untuk variant profile
  $profileTab = $profileTab ?? 'beranda'; // 'beranda' | 'profil'
@endphp

<header class="bg-white border-b border-slate-200">
  {{-- VARIANT: PROFILE (beranda | profil | bell) --}}
  @if($topbarVariant === 'profile')
    <div class="px-4 md:px-8 py-3 md:py-4 flex items-center justify-between gap-3">
      {{-- kiri: kosong (desktop) / judul kecil (mobile biar ga kosong banget) --}}
      <div class="min-w-0">
        <div class="md:hidden text-[12px] font-extrabold text-slate-700 truncate">
          {{ $pageTitle }}
        </div>
      </div>

      {{-- kanan: tabs + notif --}}
      <div class="flex items-center gap-3 md:gap-6">
        <a href="{{ route('student.dashboard') }}"
           class="text-[12px] md:text-[13px] font-black md:font-bold
                  {{ $profileTab === 'beranda' ? 'text-slate-900' : 'text-slate-400 hover:text-slate-600' }}">
          Beranda
        </a>

        <a href="{{ route('student.profile') }}"
           class="relative text-[12px] md:text-[13px] font-black md:font-bold
                  {{ $profileTab === 'profil' ? 'text-slate-900' : 'text-slate-400 hover:text-slate-600' }}">
          Profil
          @if($profileTab === 'profil')
            <span class="absolute left-0 -bottom-2 md:-bottom-3 h-[2px] w-full bg-slate-900 rounded-full"></span>
          @endif
        </a>

        <button type="button"
                class="h-10 w-10 rounded-xl border border-slate-200 bg-white flex items-center justify-center hover:bg-slate-50">
          <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none">
            <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7"
                  stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"
                  stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
          </svg>
        </button>
      </div>
    </div>

  {{-- VARIANT: DEFAULT (title kiri + search + notif + user) --}}
  @else
    <div class="px-4 md:px-8 py-3 md:py-4 flex items-center justify-between gap-3">

      {{-- kiri: title --}}
      <div class="min-w-0">
        <div class="text-[13px] md:text-sm font-extrabold md:font-semibold text-slate-700 truncate">
          {{ $pageTitle }}
        </div>

        {{-- optional subtext mobile (biar lebih hidup) --}}
        <div class="md:hidden text-[11px] text-slate-400 font-semibold truncate">
          {{ auth()->user()->name }}
        </div>
      </div>

      {{-- kanan: action --}}
      <div class="flex items-center gap-2 md:gap-4 shrink-0">

        {{-- Mobile: tombol search (kalau mau nanti bisa jadi modal/search page) --}}
        <button type="button"
                class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white flex items-center justify-center hover:bg-slate-50"
                aria-label="Search">
          <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none">
            <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="1.7"/>
            <path d="M16.5 16.5 21 21" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
          </svg>
        </button>

        {{-- Desktop: search input --}}
        <div class="hidden md:flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 w-[360px]">
          <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none">
            <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="1.7"/>
            <path d="M16.5 16.5 21 21" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
          </svg>
          <input
            class="bg-transparent outline-none text-sm w-full placeholder:text-slate-400"
            placeholder="Cari dosen atau dokumen..."
          />
        </div>

        {{-- notif --}}
        <button type="button"
                class="h-10 w-10 rounded-xl border border-slate-200 bg-white flex items-center justify-center hover:bg-slate-50">
          <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none">
            <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7"
                  stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"
                  stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
          </svg>
        </button>

        {{-- Desktop: user detail --}}
        <div class="hidden md:flex items-center gap-3">
          <div class="text-right leading-4">
            <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
            <div class="text-[11px] text-slate-400">{{ auth()->user()->email }}</div>
          </div>
          <div class="h-10 w-10 rounded-full bg-slate-200 overflow-hidden">
            <svg viewBox="0 0 24 24" class="h-10 w-10 text-slate-500">
              <path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.2 0-7.5 2.1-7.5 4.5V21h15v-2.5C19.5 16.1 16.2 14 12 14Z"/>
            </svg>
          </div>
        </div>

        {{-- Mobile: avatar kecil --}}
        <div class="md:hidden h-10 w-10 rounded-full bg-slate-200 overflow-hidden flex items-center justify-center">
          <svg viewBox="0 0 24 24" class="h-8 w-8 text-slate-500">
            <path fill="currentColor" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.2 0-7.5 2.1-7.5 4.5V21h15v-2.5C19.5 16.1 16.2 14 12 14Z"/>
          </svg>
        </div>

      </div>
    </div>
  @endif
</header>