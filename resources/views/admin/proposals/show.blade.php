@extends('layouts.app')

@section('content')
{{-- SweetAlert2 & Animate.css --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="relative min-h-[calc(100vh-64px)]">

    {{-- Overlay Background --}}
    <div class="fixed inset-0 bg-[#0B1220]/55 backdrop-blur-[1px]"></div>

    {{-- Modal Container --}}
    <div class="fixed inset-0 flex items-center justify-center px-4 py-8 z-50">
        <div class="w-full max-w-[640px] bg-white rounded-[24px] shadow-[0_20px_60px_rgba(0,0,0,0.18)] border border-[#EEF0F3] overflow-hidden animate__animated animate__zoomIn animate__faster">

            {{-- Header --}}
            <div class="px-8 pt-7 pb-4 flex items-start justify-between">
                <div>
                    <h1 class="text-[18px] font-black text-[#1C252E] tracking-tight">Detail Pengajuan Mahasiswa</h1>
                    <p class="text-[12px] text-[#637381] font-medium mt-1">Tinjau usulan judul dan kelengkapan berkas mahasiswa.</p>
                </div>
                <a href="{{ route('admin.proposals.index') }}" class="w-10 h-10 rounded-xl bg-[#F4F6F8] hover:bg-[#EEF0F3] transition flex items-center justify-center group">
                    <svg class="w-5 h-5 text-[#637381] group-hover:rotate-90 transition-transform duration-300" viewBox="0 0 24 24" fill="none"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                </a>
            </div>

            {{-- Body --}}
            <div class="px-8 pb-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6 border-b border-[#F4F6F8] pb-6">
                    <div>
                        <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Nama Lengkap</div>
                        <div class="mt-1.5 text-[14px] font-extrabold text-[#212B36]">{{ $proposal->student_name }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">NPM / Identitas</div>
                        <div class="mt-1.5 text-[14px] font-extrabold text-[#212B36]">{{ $proposal->student_id }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Estimasi Lulus</div>
                        <div class="mt-1.5 text-[13px] font-bold text-[#454F5B]">{{ $proposal->graduation_estimate ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Bidang Minat</div>
                        <div class="mt-2 inline-flex items-center px-3 py-1 rounded-lg bg-[#EEF4FF] text-[#007BFF] text-[10px] font-black tracking-wider uppercase">{{ $proposal->topic->name ?? '-' }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Judul Skripsi</div>
                    <div class="mt-2.5 bg-[#F9FAFB] border border-[#EEF0F3] rounded-[14px] px-5 py-4 text-[13px] font-bold text-[#1C252E] leading-relaxed italic">“{{ $proposal->title }}”</div>
                </div>

                <div class="mt-6">
                    <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Alasan Memilih Pembimbing</div>
                    <div class="mt-2.5 bg-white border border-[#EEF0F3] rounded-[14px] px-5 py-4 text-[13px] text-[#454F5B] leading-relaxed">
                        <div class="border-l-4 border-[#DCE6FF] pl-4 text-[#637381] font-medium whitespace-pre-line">{{ $proposal->abstract }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Lampiran Bukti KRS</div>
                    <div class="mt-3 border border-[#EEF0F3] rounded-[16px] px-4 py-3.5 flex items-center justify-between gap-3 hover:bg-[#F9FAFB] transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-[#FFE8E8] flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="min-w-0"><div class="text-[12px] font-black text-[#212B36] truncate">{{ $proposal->krs_file ? basename($proposal->krs_file) : 'Belum ada file' }}</div><div class="text-[11px] text-[#919EAB] font-bold">DOKUMEN_KRS.PDF</div></div>
                        </div>
                        @if($proposal->krs_file)
                            <a href="{{ asset('storage/'.$proposal->krs_file) }}" target="_blank" class="px-4 py-2 rounded-lg bg-[#F4F6F8] text-[11px] font-black text-[#1C252E] hover:bg-[#1C252E] hover:text-white transition-all uppercase tracking-wider">Pratinjau</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-8 py-6 border-t border-[#F4F6F8] bg-[#FBFCFE] flex flex-col sm:flex-row items-center justify-between gap-4">
                {{-- Tombol Tolak --}}
                <form method="POST" action="{{ route('admin.proposals.status', $proposal) }}" id="rejectForm" class="w-full sm:w-auto">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <input type="hidden" name="rejection_reason" id="rejection_reason_input">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl border-2 border-[#FFE8E8] text-[#E11D48] bg-white text-[12px] font-black uppercase tracking-widest hover:bg-[#E11D48] hover:text-white transition-all duration-200">Tolak Pengajuan</button>
                </form>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.proposals.index') }}" class="flex-1 sm:flex-none text-center px-6 py-3 rounded-xl text-[12px] font-black text-[#637381] hover:bg-[#F4F6F8] transition uppercase tracking-widest">Batal</a>
                    {{-- Tombol Setujui --}}
                    <form method="POST" action="{{ route('admin.proposals.status', $proposal) }}" id="approveForm" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="status" value="verified">
                        <button type="submit" class="w-full px-7 py-3 rounded-xl bg-[#1677FF] text-white text-[12px] font-black uppercase tracking-widest shadow-[0_10px_25px_rgba(22,119,255,0.25)] hover:shadow-none hover:scale-[0.98] transition-all">Setujui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT LOGIC --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const rejectForm = document.getElementById('rejectForm');
    const approveForm = document.getElementById('approveForm');

    // Fungsi Loading State
    function showLoading() {
        Swal.fire({
            title: 'Sedang Memproses...',
            text: 'Sistem sedang memperbarui database dan mengirim notifikasi email ke mahasiswa. Mohon tunggu.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // 1. Handler Setuju
    approveForm.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'question',
            title: 'Setujui Pengajuan?',
            text: 'Email persetujuan akan otomatis dikirim ke mahasiswa.',
            showCancelButton: true,
            confirmButtonColor: '#1677FF',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                this.submit();
            }
        });
    });

    // 2. Handler Tolak (Input Alasan)
    rejectForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const { value: reason } = await Swal.fire({
            title: 'Tolak Pengajuan?',
            text: "Berikan alasan penolakan agar mahasiswa dapat memperbaikinya.",
            input: 'textarea',
            inputPlaceholder: 'Contoh: Berkas KRS tidak terbaca atau judul kurang spesifik...',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            confirmButtonText: 'Ya, Tolak Sekarang',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) return 'Wajib mengisi alasan penolakan!'
            }
        });

        if (reason) {
            document.getElementById('rejection_reason_input').value = reason;
            showLoading();
            this.submit();
        }
    });

    // 3. Feedback Setelah Redirect (Hanya muncul jika ada session success)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#1677FF',
            timer: 4000,
            timerProgressBar: true
        });
    @endif
});
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endsection