@extends('layouts.app')

@section('content')
<div class="bg-[#F6F7FB] min-h-screen p-4 md:p-8 font-['Inter',_sans-serif]">
    <div class="max-w-6xl mx-auto animate__animated animate__fadeIn">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-[22px] md:text-[26px] font-black text-[#1C252E]">Pengajuan Masuk</h1>
                <p class="text-[13px] text-[#637381] mt-1 font-medium">Kelola dan verifikasi berkas pengajuan skripsi mahasiswa.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-[11px] font-bold text-[#919EAB] bg-white border border-[#EEF0F3] px-4 py-2 rounded-full shadow-sm">
                    Total: {{ $proposals->total() }} Pengajuan
                </div>
            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white border border-[#EEF0F3] rounded-[20px] shadow-[0_4px_25px_rgba(0,0,0,0.03)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-black uppercase tracking-widest text-[#919EAB] border-b border-[#F4F6F8] bg-[#FBFCFE]">
                            <th class="py-5 px-6">Mahasiswa</th>
                            <th class="py-5 px-6">Identitas</th>
                            <th class="py-5 px-6">Topik & Bidang</th>
                            <th class="py-5 px-6">Dosen Pilihan</th>
                            <th class="py-5 px-6">Status</th>
                            <th class="py-5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F4F6F8]">
                        @forelse($proposals as $p)
                            <tr class="group hover:bg-[#F9FAFB] transition-all duration-200">
                                {{-- MAHASISWA --}}
                                <td class="py-5 px-6">
                                    <div class="font-extrabold text-[14px] text-[#1C252E] group-hover:text-[#007BFF] transition-colors leading-tight">
                                        {{ $p->student_name }}
                                    </div>
                                    <div class="text-[11px] text-[#919EAB] font-bold mt-1 uppercase tracking-tight">S1 Informatika</div>
                                </td>

                                {{-- NPM --}}
                                <td class="py-5 px-6">
                                    <span class="text-[13px] font-bold text-[#454F5B]">{{ $p->student_id }}</span>
                                </td>

                                {{-- TOPIK --}}
                                <td class="py-5 px-6">
                                    <span class="px-3 py-1 rounded-lg bg-[#EEF4FF] text-[#007BFF] text-[10px] font-black uppercase tracking-tighter">
                                        {{ $p->topic->name ?? 'UMUM' }}
                                    </span>
                                </td>

                                {{-- DOSEN --}}
                                <td class="py-5 px-6 text-[#454F5B] font-semibold text-[13px]">
                                    {{ $p->selectedDosen->name ?? '-' }}
                                </td>

                                {{-- STATUS (Bahasa Indonesia) --}}
                                <td class="py-5 px-6">
                                    @php
                                        // Mapping warna dan teks ke Bahasa Indonesia
                                        [$statusText, $badgeColor] = match($p->status) {
                                            'pending'  => ['MENUNGGU', 'bg-[#FFF9E6] text-[#B78103] border-[#FFEBB3]'],
                                            'rejected' => ['DITOLAK', 'bg-[#FFE8E8] text-[#B42318] border-[#FFD0D0]'],
                                            'verified' => ['DISETUJUI', 'bg-[#EBFBEE] text-[#118D57] border-[#CFF7D6]'],
                                            default    => [strtoupper($p->status), 'bg-slate-50 text-slate-500 border-slate-200']
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-lg border px-3 py-1 text-[10px] font-black uppercase tracking-tight {{ $badgeColor }}">
                                        {{ $statusText }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="py-5 px-6 text-right">
                                    <a href="{{ route('admin.proposals.show', $p) }}"
                                       class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-[10px] bg-white border border-[#EEF0F3] text-[12px] font-extrabold text-[#1C252E] shadow-sm transition-all duration-200 hover:border-[#007BFF] hover:text-[#007BFF] hover:scale-105 active:scale-95">
                                        Detail
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3 text-slate-400">
                                        <svg class="w-12 h-12 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l5 5v11a2 2 0 0 1-2 2z"/></svg>
                                        <p class="text-[13px] font-medium italic">Belum ada pengajuan masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-8 py-5 bg-[#F9FAFB] border-t border-[#F4F6F8]">
                {{ $proposals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection