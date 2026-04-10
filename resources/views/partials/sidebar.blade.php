@php
  $nav = [
    [
      'label' => 'Dashboard',
      'href'  => route('student.dashboard'),
      'key'   => 'dashboard',
      'icon'  => 'home',
      'active_when' => ['student.dashboard'],
    ],
    [
      'label' => 'Pengajuan',
      'href'  => route('student.proposal.create'),
      'key'   => 'pengajuan',
      'icon'  => 'file',
      'active_when' => ['student.proposal.*'],
    ],
    [
      'label' => 'Dosen',
      'href'  => route('student.dosen.index'),
      'key'   => 'dosen',
      'icon'  => 'users',
      'active_when' => ['student.dosen.*', 'mahasiswa/dosen*'],
    ],
    [
      'label' => 'Profil',
      'href'  => route('student.profile'),
      'key'   => 'profil',
      'icon'  => 'user',
      'active_when' => ['student.profile'],
    ],
  ];

  $isActiveFor = function(array $patterns) {
    foreach ($patterns as $p) {
      if (str_contains($p, '/')) {
        if (request()->is($p)) return true;
      } else {
        if (request()->routeIs($p)) return true;
      }
    }
    return false;
  };

  $icon = function(string $name) {
    if ($name === 'home') {
      return '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
        <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1v-9.5Z"
              stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
      </svg>';
    }
    if ($name === 'file') {
      return '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
        <path d="M7 3h7l3 3v15a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"
              stroke="currentColor" stroke-width="1.7"/>
        <path d="M14 3v4h4" stroke="currentColor" stroke-width="1.7"/>
        <path d="M8.5 12h7M8.5 15h7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
      </svg>';
    }
    if ($name === 'users') {
      return '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
              stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
        <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"
              stroke="currentColor" stroke-width="1.7"/>
        <path d="M22 21v-2a3.5 3.5 0 0 0-2.5-3.35"
              stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
        <path d="M16.5 3.65a4 4 0 0 1 0 7.7"
              stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
      </svg>';
    }
    // user
    return '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
      <path d="M20 21v-2a4 4 0 0 0-3-3.87"
            stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
      <path d="M4 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"
            stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
      <path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"
            stroke="currentColor" stroke-width="1.7"/>
    </svg>';
  };

  $logoutIcon = function() {
    return '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
      <path d="M10 17l-1 0a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4h1"
            stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
      <path d="M14 7l5 5-5 5"
            stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M19 12H10"
            stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
    </svg>';
  };
@endphp

{{-- =========================
   DESKTOP SIDEBAR (md+)
   ========================= --}}
<aside class="hidden md:block w-[260px] bg-white border-r border-slate-200 min-h-screen sticky top-0">
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
        <div class="text-[11px] text-slate-400 mt-0.5">INFORMATIKA S1</div>
      </div>
    </div>
  </div>

  <nav class="px-4 pt-2">
    <div class="space-y-1.5">
      @foreach($nav as $item)
        @php
          $isActive = $isActiveFor($item['active_when']);
          $base   = "flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition";
          $active = "bg-slate-100 text-slate-900 font-semibold";
          $idle   = "text-slate-600 hover:bg-slate-50 hover:text-slate-800";
        @endphp

        <a href="{{ $item['href'] }}" class="{{ $base }} {{ $isActive ? $active : $idle }}">
          {!! $icon($item['icon']) !!}
          <span>{{ $item['label'] }}</span>
        </a>
      @endforeach
    </div>

    <div class="mt-10 px-2">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex items-center gap-2 text-sm text-rose-500 hover:text-rose-600">
          {!! $logoutIcon() !!}
          <span>Keluar</span>
        </button>
      </form>
    </div>
  </nav>
</aside>

{{-- ======================================
   MOBILE BOTTOM NAV (BALANCED + SMOOTH CURVE)
   ====================================== --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 pb-[env(safe-area-inset-bottom)]">
  <div class="relative bg-white border-t border-slate-200 h-16">

    {{-- Smooth curve notch --}}
    <div class="absolute left-1/2 -translate-x-1/2 -top-8 w-28 h-14 bg-white rounded-t-[999px] shadow-sm"></div>

    {{-- CONTENT WRAPPER --}}
    <div class="relative flex justify-between items-center h-full px-6">

      {{-- LEFT SIDE --}}
      <div class="flex items-center gap-8">
        {{-- Pengajuan --}}
        @php $active = request()->routeIs('student.proposal.*'); @endphp
        <a href="{{ route('student.proposal.create') }}"
           class="relative flex flex-col items-center text-[10px] font-bold
                  {{ $active ? 'text-slate-900' : 'text-slate-500' }}">
          {!! $icon('file') !!}
          <span>Pengajuan</span>
          @if($active)
            <span class="absolute -bottom-1 h-[3px] w-8 bg-blue-600 rounded-full"></span>
          @endif
        </a>

        {{-- Dosen --}}
        @php $active = request()->routeIs('student.dosen.*'); @endphp
        <a href="{{ route('student.dosen.index') }}"
           class="relative flex flex-col items-center text-[10px] font-bold
                  {{ $active ? 'text-slate-900' : 'text-slate-500' }}">
          {!! $icon('users') !!}
          <span>Dosen</span>
          @if($active)
            <span class="absolute -bottom-1 h-[3px] w-8 bg-blue-600 rounded-full"></span>
          @endif
        </a>
      </div>

      {{-- RIGHT SIDE --}}
      <div class="flex items-center gap-8">
        {{-- Profil --}}
        @php $active = request()->routeIs('student.profile'); @endphp
        <a href="{{ route('student.profile') }}"
           class="relative flex flex-col items-center text-[10px] font-bold
                  {{ $active ? 'text-slate-900' : 'text-slate-500' }}">
          {!! $icon('user') !!}
          <span>Profil</span>
          @if($active)
            <span class="absolute -bottom-1 h-[3px] w-8 bg-blue-600 rounded-full"></span>
          @endif
        </a>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
            class="flex flex-col items-center text-[10px] font-bold text-rose-500 active:scale-95">
            {!! $logoutIcon() !!}
            <span>Keluar</span>
          </button>
        </form>
      </div>

      {{-- CENTER DASHBOARD --}}
      @php $dashActive = request()->routeIs('student.dashboard'); @endphp
      <div class="absolute left-1/2 -translate-x-1/2 -top-6">
        <a href="{{ route('student.dashboard') }}"
           class="h-14 w-14 rounded-full flex items-center justify-center border-4 border-white
                  shadow-xl transition-all duration-200 active:scale-90
                  {{ $dashActive
                      ? 'bg-blue-600 text-white shadow-blue-300/60 scale-105'
                      : 'bg-slate-900 text-white' }}">
          {!! $icon('home') !!}
        </a>
      </div>

    </div>
  </div>
</nav>