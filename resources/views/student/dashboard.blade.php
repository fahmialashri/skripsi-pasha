@extends('layouts.app')

@php
  $pageTitle = 'Dashboard Utama';
  $topbarVariant = 'default';
@endphp

@section('content')
<div class="font-['Inter',_sans-serif] text-[#212B36]">

    {{-- HEADER + CTA (mobile stack, tombol full width) --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-[22px] md:text-[32px] font-extrabold tracking-tight leading-tight">
                Halo, {{ $user->name }}! 👋
            </h1>
            <p class="text-[12px] md:text-[14px] text-[#637381] font-medium mt-1">
                Selamat datang di portal tugas akhir. Silakan mulai langkah awal penelitian Anda.
            </p>
        </div>

        <a href="{{ route('student.proposal.create') }}"
            class="bg-[#637381] hover:bg-[#454F5B] text-white px-5 py-3 rounded-[12px]
                   flex items-center justify-center gap-2 font-bold text-[13px] md:text-[14px]
                   transition-all shadow-md w-full md:w-fit">
            <span class="w-6 h-6 rounded-full bg-white/15 flex items-center justify-center leading-none text-lg font-light">
                +
            </span>
            Buat Pengajuan Baru
        </a>
    </div>

    {{-- STATUS CARD --}}
    <div class="bg-white border border-[#EEF0F3] p-5 md:p-8 rounded-[18px] md:rounded-[20px] flex items-start gap-4 md:gap-6
                shadow-[0_4px_12px_0_rgba(145,158,171,0.08)] mb-10">
        <div class="bg-[#FFF9EB] p-3 md:p-4 rounded-full flex items-center justify-center shrink-0">
            <i data-lucide="info" class="w-6 h-6 md:w-7 md:h-7 text-[#FFAB00]"></i>
        </div>

        <div class="flex-1">
            @php
                $status = $myProposal->status ?? null;
            @endphp

            <h4 class="text-[14px] md:text-[16px] font-bold">
                Status Pengajuan:
                <span
                    class="
                    @if(!$myProposal) text-[#B76E00]
                    @elseif($status === 'pending') text-[#FF9800]
                    @elseif($status === 'verified') text-[#007BFF]
                    @elseif($status === 'assigned') text-[#22C55E]
                    @endif
                    "
                >
                    @if(!$myProposal)
                        Belum Mengajukan
                    @elseif($status === 'pending')
                        Menunggu Verifikasi
                    @elseif($status === 'verified')
                        Telah Diverifikasi
                    @elseif($status === 'assigned')
                        Dosen Pembimbing Telah Ditentukan
                    @else
                        {{ ucfirst($status) }}
                    @endif
                </span>
            </h4>

            <p class="text-[12px] md:text-[14px] text-[#637381] mt-2 leading-relaxed">
                @if(!$myProposal)
                    Anda belum memulai proses pengajuan dosen pembimbing skripsi.
                    Segera siapkan topik dan bidang minat Anda sebelum batas waktu berakhir.
                @elseif($myProposal->status === 'pending')
                    <span class="text-amber-600 font-bold">Sedang Ditinjau:</span>
                    Pengajuan Anda sedang dalam proses peninjauan oleh admin. Mohon menunggu hingga proses verifikasi selesai.
                @elseif($myProposal->status === 'rejected')
                    <span class="text-rose-600 font-bold">Pengajuan Ditolak:</span>
                    Maaf, pengajuan Anda belum dapat disetujui oleh admin. Silakan cek alasan penolakan atau ajukan kembali dengan data yang diperbaiki.
                @elseif($myProposal->status === 'verified')
                    <span class="text-green-600 font-bold">Terverifikasi:</span>
                    Selamat! Pengajuan Anda telah disetujui. Silakan hubungi
                    <b class="text-slate-900">{{ $myProposal->selectedDosen->name ?? 'Dosen Terkait' }}</b>
                    untuk memulai proses bimbingan skripsi.
                @else
                    Status pengajuan Anda sedang diproses oleh sistem.
                @endif
            </p>

            <div class="mt-4 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('student.proposal.create') }}"
                   class="inline-block bg-[#1C252E] text-white px-6 py-3 rounded-[10px]
                          text-[13px] font-bold hover:bg-black transition text-center w-full sm:w-auto">
                    Mulai Sekarang
                </a>

                <a href="https://drive.google.com/file/d/1Zik5dXFCj6v53Urt3OIaaw4OTaVyiwNW/view"
                   target="_blank"
                   class="inline-block bg-white border border-[#919EAB]/32 text-[#212B36] px-6 py-3 rounded-[10px]
                          text-[13px] font-bold hover:bg-[#F4F6F8] transition text-center w-full sm:w-auto">
                    Lihat Panduan
                </a>
            </div>
        </div>
    </div>

    {{-- ALUR PENGAJUAN (mobile 2 kolom) --}}
    <div class="mb-12">
        <div class="flex items-center gap-2 mb-6">
            <i data-lucide="clipboard-list" class="w-6 h-6 text-[#007BFF]"></i>
            <h2 class="text-[15px] md:text-[18px] font-bold text-[#212B36]">Alur Pengajuan Skripsi</h2>
        </div>

        @php
            $steps = [
                ['Pilih Bidang Minat', 'Tentukan fokus penelitian yang sesuai dengan minat Anda.'],
                ['Cari Dosen', 'Lihat daftar dosen yang memiliki keahlian di bidang tersebut.'],
                ['Submit Proposal', 'Unggah ringkasan ide judul dan draft awal skripsi Anda.'],
                ['Verifikasi', 'Tunggu persetujuan dari Koordinator Prodi.']
            ];
        @endphp

        {{-- default: 2 kolom, md: 2 kolom, lg: 4 kolom --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
            @foreach($steps as $i => $step)
                <div class="bg-white p-4 md:p-7 rounded-[14px] md:rounded-[16px] border border-[#EEF0F3] shadow-sm">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[13px] font-bold mb-4 shadow-inner
                        {{ $i === 0 ? 'bg-[#1C252E] text-white' : 'bg-[#EEF0F3] text-[#637381]' }}">
                        {{ $i + 1 }}
                    </div>

                    <h5 class="font-bold text-[13px] md:text-[15px] text-[#212B36] mb-2 leading-tight">
                        {{ $step[0] }}
                    </h5>
                    <p class="text-[11px] md:text-[12px] text-[#637381] leading-relaxed font-medium">
                        {{ $step[1] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- PENGUMUMAN + BANTUAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 md:gap-6">
        <div class="lg:col-span-2 bg-white rounded-[18px] md:rounded-[20px] border border-[#EEF0F3] p-5 md:p-8 shadow-sm">
            <h4 class="text-[14px] md:text-[16px] font-bold flex items-center gap-2 mb-6 md:mb-8 text-[#212B36]">
                <i data-lucide="megaphone" class="w-5 h-5 text-[#007BFF]"></i>
                Pengumuman Terbaru
            </h4>

            {{-- ✅ DINAMIS DARI DB (max 2) --}}
            <div class="space-y-6 md:space-y-8">
                @forelse($announcements as $a)

                    @php
                        $icon = match($a->category ?? 'informasi') {
                            'panduan' => 'paperclip',
                            'jadwal' => 'clock',
                            default => 'megaphone',
                        };

                        $url = $a->external_url
                            ? $a->external_url
                            : ($a->file_path ? asset('storage/'.$a->file_path) : '#');

                        $btnText = ($a->action_type ?? 'download') === 'download'
                            ? 'UNDUH'
                            : 'LIHAT';
                    @endphp

                    <div class="flex items-start sm:items-center justify-between gap-4 group
                        @if(!$loop->first) border-t border-slate-50 pt-6 @endif">

                        <div class="flex items-start sm:items-center gap-4 text-[#212B36] min-w-0">
                            <div class="bg-[#F4F6F8] p-3 rounded-[10px] shrink-0">
                                <i data-lucide="{{ $icon }}" class="w-6 h-6 text-[#637381]"></i>
                            </div>

                            <div class="min-w-0">
                                <h5 class="text-[13px] md:text-[14px] font-bold hover:text-blue-600 transition-colors truncate">
                                    {{ $a->title }}
                                </h5>
                                <p class="text-[11px] md:text-[12px] text-[#919EAB] mt-1 font-medium">
                                    Diposting oleh {{ $a->posted_by ?? 'Admin' }} •
                                    {{ $a->created_at?->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        @if($url !== '#')
                            <a href="{{ $url }}" target="_blank"
                               class="text-[#007BFF] text-[12px] font-extrabold tracking-tight hover:underline shrink-0">
                                {{ $btnText }}
                            </a>
                        @else
                            <span class="text-[#919EAB] text-[12px] font-extrabold tracking-tight shrink-0">-</span>
                        @endif
                    </div>

                @empty
                    <div class="text-center text-[12px] font-bold text-[#637381] py-6">
                        Belum ada pengumuman.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-[#F0F4FF] rounded-[18px] md:rounded-[20px] p-7 md:p-10 flex flex-col items-center text-center relative overflow-hidden">
            <h4 class="text-[15px] md:text-[18px] font-extrabold text-[#212B36] mb-3 relative z-10">
                Butuh Bantuan?
            </h4>
            <p class="text-[12px] md:text-[13px] text-[#637381] leading-[1.6] mb-6 md:mb-8 relative z-10 font-medium opacity-90 italic">
                Jika Anda memiliki kendala teknis atau pertanyaan mengenai skripsi, silakan hubungi pusat bantuan.
            </p>

            <a href="https://wa.me/6281234567890?text=Halo%20Pusat%20Bantuan,%20saya%20butuh%20bantuan%20terkait%20sistem..." 
            target="_blank" 
            class="block w-full">
                <button type="button" class="bg-[#1C252E] text-white w-full py-4 rounded-[12px] flex items-center justify-center gap-3 font-bold text-sm hover:bg-black transition-all shadow-lg relative z-10">
                    <i data-lucide="life-buoy" class="w-5 h-5 opacity-90"></i>
                    Pusat Bantuan
                </button>
            </a>
            <div class="absolute -bottom-8 -right-4 opacity-[0.03] pointer-events-none text-[160px] font-black select-none">?</div>
        </div>
    </div>

</div>
@endsection