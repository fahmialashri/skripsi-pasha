@extends('layouts.app')

@section('content')
{{-- SweetAlert2 & Animate.css --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

@php
    $selectedLecturer = $proposal->selectedDosen;
    $kaprodiRecommendedLecturer = $proposal->kaprodiRecommendedDosen ?? null;

    $selectedQuota = (int)($selectedLecturer->quota ?? 0);
    $selectedUsed  = (int)($selectedLecturer->assigned_count ?? 0);
    $selectedLeft  = max(0, $selectedQuota - $selectedUsed);
    $selectedFull  = $selectedLecturer ? ($selectedQuota > 0 && $selectedUsed >= $selectedQuota) : false;
    $selectedPercent = $selectedQuota > 0 ? min(100, round(($selectedUsed / $selectedQuota) * 100)) : 0;

    $recommendedDosens = collect($recommendedDosens ?? []);
    $allDosens = collect($allDosens ?? []);

    $recommendedAvailable = $recommendedDosens->filter(function ($d) {
        $quota = (int)($d->quota ?? 0);
        $used  = (int)($d->assigned_count ?? 0);
        return $quota === 0 || $used < $quota;
    });

    $recommendedFull = $recommendedDosens->filter(function ($d) {
        $quota = (int)($d->quota ?? 0);
        $used  = (int)($d->assigned_count ?? 0);
        return $quota > 0 && $used >= $quota;
    });

    $existingRecommendedId = old('recommended_dosen_id');
    $existingManualId = old('manual_dosen_id');

    if (!$existingRecommendedId && $kaprodiRecommendedLecturer) {
        $isInRecommended = $recommendedDosens->contains(function ($d) use ($kaprodiRecommendedLecturer) {
            return (int)$d->id === (int)$kaprodiRecommendedLecturer->id;
        });

        if ($isInRecommended) {
            $existingRecommendedId = $kaprodiRecommendedLecturer->id;
        } else {
            $existingManualId = $kaprodiRecommendedLecturer->id;
        }
    }
@endphp

<div class="relative min-h-[calc(100vh-64px)]">

    {{-- Overlay Background --}}
    <div class="fixed inset-0 bg-[#0B1220]/55 backdrop-blur-[1px]"></div>

    {{-- Modal Container --}}
    <div class="fixed inset-0 flex items-center justify-center px-4 py-8 z-50">
        <div class="w-full max-w-[880px] bg-white rounded-[24px] shadow-[0_20px_60px_rgba(0,0,0,0.18)] border border-[#EEF0F3] overflow-hidden animate__animated animate__zoomIn animate__faster">

            {{-- Header --}}
            <div class="px-8 pt-7 pb-4 flex items-start justify-between">
                <div>
                    <h1 class="text-[18px] font-black text-[#1C252E] tracking-tight">Detail Pengajuan Mahasiswa</h1>
                    <p class="text-[12px] font-medium mt-1 text-[#637381]">
                        Tinjau usulan judul, pilihan pembimbing, kuota dosen, dan rekomendasi pembimbing pengganti.
                    </p>
                </div>
                <a href="{{ route('admin.proposals.index') }}" class="w-10 h-10 rounded-xl bg-[#F4F6F8] hover:bg-[#EEF0F3] transition flex items-center justify-center group">
                    <svg class="w-5 h-5 text-[#637381] group-hover:rotate-90 transition-transform duration-300" viewBox="0 0 24 24" fill="none">
                        <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </a>
            </div>

            {{-- Body --}}
            <div class="px-8 pb-6 max-h-[68vh] overflow-y-auto custom-scrollbar">
                {{-- IDENTITAS MAHASISWA --}}
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
                        <div class="mt-2 inline-flex items-center px-3 py-1 rounded-lg bg-[#EEF4FF] text-[#007BFF] text-[10px] font-black tracking-wider uppercase">
                            {{ $proposal->topic->name ?? '-' }}
                        </div>
                    </div>
                </div>

                {{-- JUDUL --}}
                <div class="mt-6">
                    <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Judul Skripsi</div>
                    <div class="mt-2.5 bg-[#F9FAFB] border border-[#EEF0F3] rounded-[14px] px-5 py-4 text-[13px] font-bold text-[#1C252E] leading-relaxed italic">
                        “{{ $proposal->title }}”
                    </div>
                </div>

                {{-- ALASAN MEMILIH --}}
                <div class="mt-6">
                    <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Alasan Memilih Pembimbing</div>
                    <div class="mt-2.5 bg-white border border-[#EEF0F3] rounded-[14px] px-5 py-4 text-[13px] text-[#454F5B] leading-relaxed">
                        <div class="border-l-4 border-[#DCE6FF] pl-4 text-[#637381] font-medium whitespace-pre-line">{{ $proposal->abstract }}</div>
                    </div>
                </div>

                {{-- DOSEN YANG DIPILIH --}}
                <div class="mt-6">
                    <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Dosen Pembimbing yang Dipilih Mahasiswa</div>

                    <div class="mt-3 border border-[#EEF0F3] rounded-[18px] bg-white p-5">
                        @if($selectedLecturer)
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-[14px] border {{ $selectedFull ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-blue-50 text-[#1677FF] border-blue-100' }}">
                                        {{ strtoupper(mb_substr($selectedLecturer->name ?? 'D', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-[14px] font-extrabold text-[#1C252E]">
                                            {{ $selectedLecturer->name ?? '-' }}
                                        </div>
                                        <div class="text-[12px] font-bold text-[#637381] mt-0.5">
                                            {{ $selectedLecturer->title ?? 'Dosen' }}
                                        </div>

                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $selectedFull ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                                {{ $selectedFull ? 'Kuota Habis' : 'Masih Tersedia' }}
                                            </span>

                                            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-slate-50 text-slate-700 border border-slate-200">
                                                Topik: {{ $proposal->topic->name ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="min-w-[220px]">
                                    <div class="text-[11px] font-bold text-[#637381] mb-1">Pemakaian Kuota</div>
                                    <div class="flex items-center justify-between text-[11px] font-black mb-2">
                                        <span class="text-[#1C252E]">{{ $selectedUsed }}/{{ $selectedQuota }}</span>
                                        <span class="{{ $selectedFull ? 'text-rose-600' : 'text-[#1677FF]' }}">{{ $selectedPercent }}%</span>
                                    </div>

                                    <div class="w-full h-2.5 rounded-full bg-[#E9EEF5] overflow-hidden">
                                        <div class="h-2.5 rounded-full {{ $selectedFull ? 'bg-rose-500' : 'bg-[#1677FF]' }}" style="width: {{ $selectedPercent }}%"></div>
                                    </div>

                                    <div class="mt-2 text-[11px] font-bold {{ $selectedFull ? 'text-rose-600' : 'text-emerald-600' }}">
                                        Sisa kuota: {{ $selectedLeft }}
                                    </div>
                                </div>
                            </div>

                            @if($selectedFull)
                                <div class="mt-4 rounded-[14px] border border-rose-200 bg-rose-50 px-4 py-3 text-[12px] font-bold text-rose-700">
                                    Dosen yang dipilih mahasiswa saat ini <b>kuotanya habis</b>. Kaprodi bisa menolak pengajuan dengan alasan kuota habis sambil tetap memberi rekomendasi dosen pengganti.
                                </div>
                            @endif
                        @else
                            <div class="rounded-[14px] bg-amber-50 border border-amber-200 px-4 py-3 text-[12px] font-bold text-amber-700">
                                Belum ada data dosen pembimbing pilihan mahasiswa.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- REKOMENDASI KAPRODI YANG SUDAH TERSIMPAN --}}
                @if($proposal->status === 'rejected' && $kaprodiRecommendedLecturer)
                    <div class="mt-6">
                        <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Rekomendasi Dosen dari Kaprodi</div>
                        <div class="mt-3 border border-[#DCE6FF] rounded-[18px] bg-[#F8FBFF] p-5">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-[14px] border bg-blue-50 text-[#1677FF] border-blue-100">
                                    {{ strtoupper(mb_substr($kaprodiRecommendedLecturer->name ?? 'D', 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <div class="text-[14px] font-extrabold text-[#1C252E]">{{ $kaprodiRecommendedLecturer->name ?? '-' }}</div>
                                    <div class="text-[12px] font-bold text-[#637381] mt-0.5">{{ $kaprodiRecommendedLecturer->title ?? 'Dosen' }}</div>
                                    <div class="mt-2 text-[11px] font-bold text-[#637381]">
                                        Expertise: {{ $kaprodiRecommendedLecturer->expertise ?? '-' }}
                                    </div>
                                    @if($proposal->kaprodi_recommendation_note)
                                        <div class="mt-3 rounded-[12px] border border-[#DCE6FF] bg-white px-4 py-3 text-[12px] text-[#454F5B] font-medium">
                                            {{ $proposal->kaprodi_recommendation_note }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- REKOMENDASI DOSEN MUNCUL HANYA JIKA DOSEN PILIHAN MAHASISWA PENUH --}}
                @if($selectedFull)
                <div class="mt-6">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <div>
                            <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Rekomendasi Dosen Pembimbing</div>
                            <div class="text-[12px] text-[#637381] font-medium mt-1">
                                Rekomendasi berdasarkan topik: <b>{{ $proposal->topic->name ?? '-' }}</b>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 border border-[#EEF0F3] rounded-[18px] bg-[#FCFDFE] p-5">
                        @if($recommendedDosens->count() > 0)
                            <div class="space-y-3">
                                @foreach($recommendedDosens as $dosen)
                                    @php
                                        $quota = (int)($dosen->quota ?? 0);
                                        $used  = (int)($dosen->assigned_count ?? 0);
                                        $left  = max(0, $quota - $used);
                                        $full  = $quota > 0 && $used >= $quota;
                                        $checked = (int)$existingRecommendedId === (int)$dosen->id;
                                    @endphp

                                    <label class="flex items-start gap-3 p-4 rounded-[16px] border transition-all cursor-pointer {{ $full ? 'border-rose-200 bg-rose-50/60' : 'border-[#E8EDF3] bg-white hover:border-[#B8D4FF] hover:bg-[#F8FBFF]' }}">
                                        <input
                                            type="radio"
                                            name="recommended_dosen_choice"
                                            value="{{ $dosen->id }}"
                                            class="mt-1 w-4 h-4 text-[#1677FF] border-slate-300 focus:ring-[#1677FF]"
                                            {{ $full ? 'disabled' : '' }}
                                            {{ $checked ? 'checked' : '' }}
                                        >

                                        <div class="flex-1">
                                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                                <div>
                                                    <div class="text-[13px] font-extrabold text-[#1C252E]">{{ $dosen->name }}</div>
                                                    <div class="text-[11px] font-bold text-[#637381] mt-0.5">{{ $dosen->title ?? 'Dosen' }}</div>
                                                </div>

                                                <div class="flex flex-wrap gap-2">
                                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $full ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                                                        {{ $full ? 'Penuh' : 'Tersedia' }}
                                                    </span>
                                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-50 text-slate-700 border border-slate-200">
                                                        Sisa {{ $left }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mt-2 text-[11px] font-bold text-[#637381]">
                                                Expertise: {{ $dosen->expertise ?? '-' }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-[14px] bg-amber-50 border border-amber-200 px-4 py-3 text-[12px] font-bold text-amber-700">
                                Belum ada rekomendasi dosen berdasarkan topik ini.
                            </div>
                        @endif

                        @if($recommendedDosens->count() > 0 && $recommendedAvailable->count() === 0)
                            <div class="mt-4 rounded-[14px] border border-amber-200 bg-amber-50 px-4 py-3 text-[12px] font-bold text-amber-700">
                                Semua dosen yang sesuai topik saat ini <b>penuh</b>. Kaprodi dapat memilih dosen lain secara manual.
                            </div>
                        @endif

                        {{-- PILIH MANUAL --}}
                        <div class="mt-5 pt-5 border-t border-[#EEF0F3]">
                            <div class="text-[11px] font-black text-[#1C252E] uppercase tracking-wider">Pilih Manual oleh Kaprodi</div>
                            <div class="text-[12px] text-[#637381] font-medium mt-1">
                                Gunakan ini jika dosen sesuai topik penuh semua, atau kaprodi ingin menunjuk dosen lain secara langsung.
                            </div>

                            <div class="mt-3">
                                <select id="manual_dosen_select" class="w-full rounded-[14px] border border-[#E6E8EC] bg-white px-4 py-3 text-[12px] font-bold text-[#1C252E] focus:ring-2 focus:ring-[#DCE6FF] outline-none">
                                    <option value="">-- Pilih dosen manual --</option>
                                    @foreach($allDosens as $dosen)
                                        @php
                                            $quota = (int)($dosen->quota ?? 0);
                                            $used  = (int)($dosen->assigned_count ?? 0);
                                            $left  = max(0, $quota - $used);
                                            $full  = $quota > 0 && $used >= $quota;
                                        @endphp
                                        <option value="{{ $dosen->id }}" {{ (int)$existingManualId === (int)$dosen->id ? 'selected' : '' }}>
                                            {{ $dosen->name }} — {{ $dosen->title ?? 'Dosen' }} — Sisa: {{ $left }}{{ $full ? ' (Penuh)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-2 text-[11px] text-[#919EAB] font-bold">
                                Pilihan manual tetap bisa dipakai walaupun dosen penuh, sesuai keputusan kaprodi.
                            </div>
                        </div>

                        {{-- WARNING PILIH DOSEN DULU --}}
                        <div id="replacement-warning-box" class="hidden mt-5 rounded-[14px] border border-amber-200 bg-amber-50 px-4 py-3 text-[12px] font-bold text-amber-700">
                            Karena dosen pilihan mahasiswa kuotanya habis, kaprodi wajib memilih dosen rekomendasi atau dosen manual terlebih dahulu sebelum menyetujui pengajuan.
                        </div>
                    </div>
                </div>
                @endif

                {{-- LAMPIRAN --}}
                <div class="mt-6">
                    <div class="text-[10px] font-black tracking-widest text-[#919EAB] uppercase">Lampiran Bukti KRS</div>
                    <div class="mt-3 border border-[#EEF0F3] rounded-[16px] px-4 py-3.5 flex items-center justify-between gap-3 hover:bg-[#F9FAFB] transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-[#FFE8E8] flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[12px] font-black text-[#212B36] truncate">{{ $proposal->krs_file ? basename($proposal->krs_file) : 'Belum ada file' }}</div>
                                <div class="text-[11px] text-[#919EAB] font-bold">DOKUMEN_KRS.PDF</div>
                            </div>
                        </div>
                        @if($proposal->krs_file)
                            <a href="{{ asset('storage/'.$proposal->krs_file) }}" target="_blank" class="px-4 py-2 rounded-lg bg-[#F4F6F8] text-[11px] font-black text-[#1C252E] hover:bg-[#1C252E] hover:text-white transition-all uppercase tracking-wider">
                                Pratinjau
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-8 py-6 border-t border-[#F4F6F8] bg-[#FBFCFE] flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                {{-- REJECT FORM --}}
                <form method="POST" action="{{ route('admin.proposals.status', $proposal) }}" id="rejectForm" class="w-full lg:w-auto">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <input type="hidden" name="rejection_reason" id="rejection_reason_input">
                    <input type="hidden" name="recommended_dosen_id" id="reject_recommended_dosen_id" value="{{ $existingRecommendedId }}">
                    <input type="hidden" name="manual_dosen_id" id="reject_manual_dosen_id" value="{{ $existingManualId }}">

                    <button type="submit" class="w-full lg:w-auto px-6 py-3 rounded-xl border-2 border-[#FFE8E8] text-[#E11D48] bg-white text-[12px] font-black uppercase tracking-widest hover:bg-[#E11D48] hover:text-white transition-all duration-200">
                        Tolak Pengajuan
                    </button>
                </form>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                    <a href="{{ route('admin.proposals.index') }}" class="text-center px-6 py-3 rounded-xl text-[12px] font-black text-[#637381] hover:bg-[#F4F6F8] transition uppercase tracking-widest">
                        Batal
                    </a>

                    {{-- APPROVE FORM --}}
                    <form method="POST" action="{{ route('admin.proposals.status', $proposal) }}" id="approveForm" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="status" value="verified">
                        <input type="hidden" name="recommended_dosen_id" id="recommended_dosen_id" value="{{ $existingRecommendedId }}">
                        <input type="hidden" name="manual_dosen_id" id="manual_dosen_id" value="{{ $existingManualId }}">

                        <button id="approveButton" type="submit" class="w-full px-7 py-3 rounded-xl bg-[#1677FF] text-white text-[12px] font-black uppercase tracking-widest shadow-[0_10px_25px_rgba(22,119,255,0.25)] hover:shadow-none hover:scale-[0.98] transition-all">
                            Setujui
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const rejectForm = document.getElementById('rejectForm');
    const approveForm = document.getElementById('approveForm');
    const approveButton = document.getElementById('approveButton');
    const rejectionReasonInput = document.getElementById('rejection_reason_input');

    const recommendedRadios = document.querySelectorAll('input[name="recommended_dosen_choice"]');
    const manualSelect = document.getElementById('manual_dosen_select');

    const recommendedDosenIdInput = document.getElementById('recommended_dosen_id');
    const manualDosenIdInput = document.getElementById('manual_dosen_id');

    const rejectRecommendedDosenIdInput = document.getElementById('reject_recommended_dosen_id');
    const rejectManualDosenIdInput = document.getElementById('reject_manual_dosen_id');

    const replacementWarningBox = document.getElementById('replacement-warning-box');

    const selectedLecturerIsFull = @json($selectedFull);
    const selectedLecturerName = @json($selectedLecturer->name ?? null);

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

    function syncSelectedSupervisor() {
        const selectedRecommended = [...recommendedRadios].find(r => r.checked);
        const recommendedValue = selectedRecommended ? selectedRecommended.value : '';
        const manualValue = manualSelect ? (manualSelect.value || '') : '';

        if (recommendedDosenIdInput) recommendedDosenIdInput.value = recommendedValue;
        if (manualDosenIdInput) manualDosenIdInput.value = manualValue;

        if (rejectRecommendedDosenIdInput) rejectRecommendedDosenIdInput.value = recommendedValue;
        if (rejectManualDosenIdInput) rejectManualDosenIdInput.value = manualValue;
    }

    function hasReplacementChoice() {
        const hasRecommended = recommendedDosenIdInput && recommendedDosenIdInput.value !== '';
        const hasManual = manualDosenIdInput && manualDosenIdInput.value !== '';
        return hasRecommended || hasManual;
    }

    function updateApproveState() {
        syncSelectedSupervisor();

        if (!approveButton) return;

        if (!selectedLecturerIsFull) {
            approveButton.disabled = false;
            approveButton.classList.remove('opacity-60', 'cursor-not-allowed');
            if (replacementWarningBox) replacementWarningBox.classList.add('hidden');
            return;
        }

        const valid = hasReplacementChoice();

        approveButton.disabled = !valid;
        approveButton.classList.toggle('opacity-60', !valid);
        approveButton.classList.toggle('cursor-not-allowed', !valid);

        if (replacementWarningBox) {
            replacementWarningBox.classList.toggle('hidden', valid);
        }
    }

    recommendedRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.checked && manualSelect) {
                manualSelect.value = '';
            }
            updateApproveState();
        });
    });

    if (manualSelect) {
        manualSelect.addEventListener('change', () => {
            if (manualSelect.value) {
                recommendedRadios.forEach(radio => radio.checked = false);
            }
            updateApproveState();
        });
    }

    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            syncSelectedSupervisor();

            if (selectedLecturerIsFull && !hasReplacementChoice()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Dosen Pengganti',
                    text: 'Karena kuota dosen yang dipilih mahasiswa habis, kaprodi wajib memilih dosen rekomendasi atau dosen manual terlebih dahulu.',
                    confirmButtonColor: '#1677FF',
                });
                updateApproveState();
                return;
            }

            const hasRecommended = recommendedDosenIdInput && recommendedDosenIdInput.value !== '';
            const hasManual = manualDosenIdInput && manualDosenIdInput.value !== '';

            Swal.fire({
                icon: 'question',
                title: 'Setujui Pengajuan?',
                html: `
                    <div style="font-size:13px; line-height:1.6; color:#637381;">
                        Email persetujuan akan otomatis dikirim ke mahasiswa.
                        <br><br>
                        <b style="color:#1C252E;">Dosen pembimbing final:</b><br>
                        ${hasRecommended ? 'Dipilih dari rekomendasi topik.' : (hasManual ? 'Dipilih manual oleh kaprodi.' : 'Tetap menggunakan dosen yang dipilih mahasiswa / data saat ini.')}
                    </div>
                `,
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
    }

    if (rejectForm) {
        rejectForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            syncSelectedSupervisor();

            if (selectedLecturerIsFull) {
                const hasRecommended = rejectRecommendedDosenIdInput && rejectRecommendedDosenIdInput.value !== '';
                const hasManual = rejectManualDosenIdInput && rejectManualDosenIdInput.value !== '';

                const result = await Swal.fire({
                    icon: 'warning',
                    title: 'Kuota Dosen Habis',
                    html: `
                        <div style="font-size:13px; line-height:1.6; color:#637381;">
                            Dosen yang dipilih mahasiswa
                            <b style="color:#1C252E;">${selectedLecturerName ?? '-'}</b>
                            saat ini kuotanya habis.
                            <br><br>
                            Alasan penolakan akan diisi:
                            <br><b style="color:#E11D48;">"Kuota dosen habis"</b>
                            <br><br>
                            ${hasRecommended || hasManual
                                ? '<span style="color:#1677FF;font-weight:700;">Rekomendasi dosen dari kaprodi juga akan ikut disimpan.</span>'
                                : 'Kaprodinya juga bisa memilih dosen rekomendasi sebelum menolak.'}
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#E11D48',
                    confirmButtonText: 'Ya, Tolak Pengajuan',
                    cancelButtonText: 'Batal',
                });

                if (result.isConfirmed) {
                    rejectionReasonInput.value = 'Kuota dosen habis';
                    showLoading();
                    this.submit();
                    return;
                }
            }

            const { value: reason } = await Swal.fire({
                title: 'Tolak Pengajuan?',
                text: "Berikan alasan penolakan agar mahasiswa dapat memperbaikinya.",
                input: 'textarea',
                inputPlaceholder: 'Contoh: Berkas KRS tidak terbaca, judul kurang spesifik, atau kuota dosen habis...',
                showCancelButton: true,
                confirmButtonColor: '#E11D48',
                confirmButtonText: 'Ya, Tolak Sekarang',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) return 'Wajib mengisi alasan penolakan!';
                }
            });

            if (reason) {
                rejectionReasonInput.value = reason;
                showLoading();
                this.submit();
            }
        });
    }

    updateApproveState();

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