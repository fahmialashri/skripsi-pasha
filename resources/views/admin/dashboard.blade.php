@extends('layouts.app')

@section('content')
<div class="font-['Inter',_sans-serif] text-[#212B36] space-y-7 animate__animated animate__fadeIn">
    
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-[22px] md:text-[24px] font-black text-[#1C252E] tracking-tight">Panel Persetujuan Kaprodi</h1>
            <p class="text-[13px] md:text-[14px] text-[#637381] mt-1 font-medium">Pengelolaan pengajuan bimbingan skripsi mahasiswa.</p>
        </div>
        
        {{-- SEARCH BAR --}}
        <form action="{{ route('admin.dashboard') }}" method="GET" class="relative shrink-0">
            <span class="absolute inset-y-0 left-3.5 flex items-center text-[#919EAB]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau NPM..." class="pl-10 pr-4 py-2.5 bg-white border border-[#EEF0F3] rounded-[14px] text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-[#007BFF]/10 focus:border-[#007BFF] transition-all w-full sm:w-64 shadow-sm">
             @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
        </form>
    </div>

    {{-- STATS CARDS (Tetap Bisa Diklik) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $statusFilters = [
                'pending' => ['label' => 'Menunggu', 'color' => '#007BFF', 'bg' => '#EEF4FF', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                'verified' => ['label' => 'Disetujui', 'color' => '#118D57', 'bg' => '#EBFBEE', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                'rejected' => ['label' => 'Ditolak', 'color' => '#B42318', 'bg' => '#FFE8E8', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z']
            ];
            $currentStatus = request('status');
        @endphp

        @foreach($statusFilters as $status => $data)
            @php $isActive = $currentStatus === $status; @endphp
            <a href="{{ route('admin.dashboard', ['status' => $status]) }}" 
               class="group bg-white rounded-[20px] p-6 flex items-center gap-5 transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.02)] border {{ $isActive ? 'border-['.$data['color'].'] ring-2 ring-['.$data['color'].']/10' : 'border-[#EEF0F3]' }} relative overflow-hidden">
                <div class="w-12 h-12 rounded-[14px] flex items-center justify-center {{ $isActive ? 'bg-['.$data['color'].'] text-white' : 'bg-['.$data['bg'].'] text-['.$data['color'].']' }} shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="{{ $data['icon'] }}" /></svg>
                </div>
                <div>
                    <p class="text-[11px] font-black uppercase tracking-widest text-[#919EAB]">{{ $data['label'] }}</p>
                    <h3 class="text-[28px] font-black text-[#1C252E] leading-none mt-1">{{ $counts[$status] ?? 0 }}</h3>
                </div>
            </a>
        @endforeach
    </div>

    {{-- FILTER TABS (BIAR USER GAK BINGUNG) --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar">
        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 rounded-full text-[12px] font-bold transition-all {{ !request('status') ? 'bg-[#1C252E] text-white shadow-md' : 'bg-white text-[#637381] border border-[#EEF0F3] hover:bg-[#F4F6F8]' }}">
            Semua
        </a>
        <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="px-5 py-2 rounded-full text-[12px] font-bold transition-all {{ request('status') == 'pending' ? 'bg-[#007BFF] text-white shadow-md' : 'bg-white text-[#637381] border border-[#EEF0F3] hover:bg-[#F4F6F8]' }}">
            Menunggu ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.dashboard', ['status' => 'verified']) }}" class="px-5 py-2 rounded-full text-[12px] font-bold transition-all {{ request('status') == 'verified' ? 'bg-[#118D57] text-white shadow-md' : 'bg-white text-[#637381] border border-[#EEF0F3] hover:bg-[#F4F6F8]' }}">
            Disetujui ({{ $counts['verified'] }})
        </a>
        <a href="{{ route('admin.dashboard', ['status' => 'rejected']) }}" class="px-5 py-2 rounded-full text-[12px] font-bold transition-all {{ request('status') == 'rejected' ? 'bg-[#B42318] text-white shadow-md' : 'bg-white text-[#637381] border border-[#EEF0F3] hover:bg-[#F4F6F8]' }}">
            Ditolak ({{ $counts['rejected'] }})
        </a>
    </div>

    {{-- TABLE SECTION --}}
    @if($latest->count() > 0)
        <div class="bg-white border border-[#EEF0F3] rounded-[24px] shadow-[0_4px_25px_rgba(0,0,0,0.03)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-black uppercase tracking-widest text-[#919EAB] border-b border-[#F4F6F8]">
                            <th class="px-8 py-6">Mahasiswa</th>
                            <th class="px-8 py-6">Usulan Judul Skripsi</th>
                            <th class="px-8 py-6">Status</th>
                            <th class="px-8 py-6 text-right">Detil</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F4F6F8]">
                        @foreach($latest as $p)
                            <tr class="group hover:bg-[#F9FAFB]/70 transition-all duration-150">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="font-extrabold text-[14px] text-[#1C252E] group-hover:text-[#007BFF] transition-colors">{{ $p->student_name }}</div>
                                    <div class="text-[12px] text-[#919EAB] font-medium mt-1">{{ $p->student_id }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-[13px] text-[#637381] font-medium leading-relaxed max-w-[480px] line-clamp-2">
                                        {{ $p->title }}
                                    </p>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    @php
                                        $st = match($p->status) {
                                            'pending' => ['c' => 'bg-[#FFF9E6] text-[#B78103] border-[#FFEBB3]', 'l' => 'Menunggu'],
                                            'rejected' => ['c' => 'bg-[#FFE8E8] text-[#B42318] border-[#FFD0D0]', 'l' => 'Ditolak'],
                                            'verified' => ['c' => 'bg-[#EBFBEE] text-[#118D57] border-[#CFF7D6]', 'l' => 'Disetujui'],
                                            default => ['c' => 'bg-gray-50', 'l' => $p->status]
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-xl border px-4 py-1.5 text-[10px] font-black uppercase tracking-tight {{ $st['c'] }}">
                                        {{ $st['l'] }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('admin.proposals.show', $p) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-[14px] border border-[#EEF0F3] bg-white text-slate-400 transition-all hover:border-[#007BFF] hover:text-[#007BFF] hover:scale-110 active:scale-95">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="py-20 flex flex-col items-center justify-center text-center animate__animated animate__fadeIn">
            <div class="w-20 h-20 bg-[#F4F6F8] rounded-[28px] flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-[#919EAB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-[18px] font-black text-[#1C252E]">Data Tidak Ditemukan</h3>
            <p class="text-[13px] text-[#637381] mt-2 max-w-[320px] font-medium leading-relaxed">
                Tidak ada data pengajuan untuk kategori ini.
            </p>
        </div>
    @endif

</div>
@endsection