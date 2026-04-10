<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Portal Skripsi' }}</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-[#F6F7FB] text-[#212B36] font-['Inter',_sans-serif]">

  <div class="min-h-screen flex">

    {{-- =========================
       SIDEBAR (Desktop)
       ========================= --}}
    @auth
      @if(auth()->user()->role === 'admin')
        @include('partials.sidebar-admin')
      @else
        @include('partials.sidebar')
      @endif
    @endauth

    {{-- =========================
       MAIN WRAPPER
       ========================= --}}
    <div class="flex-1 flex flex-col min-h-screen">

      {{-- Topbar --}}
      @auth
        @include('partials.topbar')
      @endauth

      {{-- =========================
         MAIN CONTENT
         ========================= --}}
      <main
        class="
          flex-1
          bg-[#F6F7FB]
          px-4 md:px-8
          py-6 md:py-8
          pb-28 md:pb-8
        "
      >
        @yield('content')
      </main>

    </div>
  </div>

  {{-- Additional Scripts --}}
  @stack('scripts')

</body>
</html>