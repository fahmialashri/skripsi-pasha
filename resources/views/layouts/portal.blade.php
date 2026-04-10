<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Portal Skripsi' }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
  <div class="min-h-screen flex">

    {{-- Sidebar --}}
    @include('partials.portal-sidebar')

    {{-- Main --}}
    <div class="flex-1 flex flex-col">
      @include('partials.portal-topbar')

      <main class="px-8 py-6">
        @yield('content')
      </main>
    </div>

  </div>
</body>
</html>