@extends('layouts.app')

@php
    $pageTitle = 'Form Pengajuan';
    $topbarVariant = 'default';
@endphp

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="min-h-screen bg-[#F6F7FB] animate__animated animate__fadeIn">
    {{-- TOP MINI BAR --}}
    <div class="flex items-center justify-between px-4 md:px-6 py-4">
        <div></div>
        <a href="{{ url()->previous() }}" class="text-[12px] font-bold text-[#637381] hover:underline flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Batal
        </a>
    </div>

    {{-- CENTER WRAPPER --}}
    <div class="max-w-[500px] mx-auto px-4 pb-12">
        <div class="text-center mb-6">
            <h1 class="text-[20px] md:text-[22px] font-extrabold text-[#1C252E] tracking-tight">Form Pengajuan</h1>
            <p class="text-[12px] md:text-[13px] text-[#637381] font-medium mt-1">
                Lengkapi data di bawah untuk pengajuan dosen pembimbing.
            </p>
        </div>

        {{-- CARD --}}
        <div class="bg-white border border-[#EEF0F3] rounded-[20px] shadow-[0_8px_24px_rgba(0,0,0,0.04)] p-6 md:p-8">
            <form id="proposalForm" action="{{ route('student.proposal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label class="text-[12px] font-bold text-[#212B36] ml-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        name="student_name"
                        value="{{ old('student_name', $user->name ?? '') }}"
                        required
                        class="mt-1.5 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-medium text-[#212B36] focus:bg-white focus:ring-2 focus:ring-[#DCE6FF] focus:border-[#007BFF] outline-none transition-all"
                    />
                    @error('student_name')
                        <p class="text-[11px] text-red-500 mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NPM --}}
                <div>
                    <label class="text-[12px] font-bold text-[#212B36] ml-1">
                        NPM <span class="text-red-500">*</span>
                    </label>
                    <input
                        name="student_id"
                        value="{{ old('student_id', $user->student_id ?? '') }}"
                        placeholder="Contoh: 140810..."
                        required
                        class="mt-1.5 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-medium text-[#212B36] focus:bg-white focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all"
                        readonly
                    />
                    @error('student_id')
                        <p class="text-[11px] text-red-500 mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- WhatsApp --}}
                <div>
                    <label class="text-[12px] font-bold text-[#212B36] ml-1">
                        Nomor WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input
                        name="whatsapp"
                        value="{{ old('whatsapp') }}"
                        placeholder="08XXXXXXXXXX"
                        required
                        class="mt-1.5 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-medium text-[#212B36] focus:bg-white focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all"
                    />
                    @error('whatsapp')
                        <p class="text-[11px] text-red-500 mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estimasi Lulus --}}
                <div>
                    <label class="text-[12px] font-bold text-[#212B36] ml-1">
                        Estimasi Lulus <span class="text-red-500">*</span>
                    </label>
                    <div class="relative mt-1.5">
                        <input
                            type="date"
                            id="graduation_date"
                            required
                            class="w-full appearance-none rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-medium text-[#212B36] focus:bg-white focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all"
                            value="{{ old('graduation_date') }}"
                        >
                    </div>
                    <div class="relative mt-2">
                        <input
                            type="text"
                            id="graduation_estimate_display"
                            class="w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-medium text-[#212B36] outline-none"
                            placeholder="Semester akan terisi otomatis"
                            readonly
                        >
                        <input
                            type="hidden"
                            name="graduation_estimate"
                            id="graduation_estimate"
                            value="{{ old('graduation_estimate') }}"
                        >
                    </div>
                    @error('graduation_estimate')
                        <p class="text-[11px] text-red-500 mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Judul Skripsi --}}
                <div>
                    <label class="text-[12px] font-bold text-[#212B36] ml-1">
                        Judul Skripsi (Tentatif) <span class="text-red-500">*</span>
                    </label>
                    <input
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Masukkan usulan judul skripsi"
                        required
                        class="mt-1.5 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-medium text-[#212B36] focus:bg-white focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all"
                    />
                    @error('title')
                        <p class="text-[11px] text-red-500 mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Topik Bidang --}}
                <div>
                    <label class="text-[12px] font-bold text-[#212B36] ml-1">
                        Topik Bidang Minat <span class="text-red-500">*</span>
                    </label>
                    <div class="relative mt-1.5">
                        <select
                            name="topic_id"
                            id="topic_select"
                            required
                            class="w-full appearance-none rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-medium text-[#212B36] outline-none"
                        >
                            <option value="">Pilih Bidang Minat</option>
                            @foreach(($topics ?? []) as $topic)
                                <option value="{{ $topic->id }}" @selected(old('topic_id', $autoTopicId) == $topic->id)>
                                    {{ $topic->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[#637381]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('topic_id')
                        <p class="text-[11px] text-red-500 mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dosen --}}
                <div>
                    <label class="text-[12px] font-bold text-[#212B36] ml-1">
                        Usulan Dosen Pembimbing <span class="text-red-500">*</span>
                    </label>
                    <div class="relative mt-1.5">
                        <select
                            name="selected_dosen_id"
                            id="dosen_select"
                            required
                            class="w-full appearance-none rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-medium text-[#212B36] outline-none"
                        >
                            @if($selectedDosen)
                                <option value="{{ $selectedDosen->id }}" selected>{{ $selectedDosen->name }}</option>
                            @else
                                <option value="">-- Pilih Topik Terlebih Dahulu --</option>
                            @endif
                        </select>
                        <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[#637381]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('selected_dosen_id')
                        <p class="text-[11px] text-red-500 mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alasan --}}
                <div>
                    <label class="text-[12px] font-bold text-[#212B36] ml-1">
                        Alasan Pengajuan <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="abstract"
                        rows="4"
                        placeholder="Jelaskan alasan memilih dosen/topik ini..."
                        required
                        class="mt-1.5 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-medium text-[#212B36] focus:bg-white focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all resize-none"
                    >{{ old('abstract') }}</textarea>
                    @error('abstract')
                        <p class="text-[11px] text-red-500 mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload KRS --}}
                <div>
                    <label class="text-[12px] font-bold text-[#212B36] ml-1">
                        Upload Bukti KRS Aktif <span class="text-red-500">*</span>
                    </label>
                    <label id="dropZone" class="mt-2 block cursor-pointer rounded-[16px] border-2 border-dashed border-[#D4D8DE] bg-[#FBFCFE] p-8 text-center transition-all duration-300 hover:bg-[#F0F7FF] hover:border-[#007BFF] group">
                        <input
                            type="file"
                            id="krsInput"
                            name="krs_file"
                            class="hidden"
                            accept=".pdf,.png,.jpg,.jpeg"
                            required
                        />
                        <div id="uploadIcon" class="mx-auto w-12 h-12 rounded-full bg-white border border-[#EEF0F3] flex items-center justify-center mb-3 shadow-sm transition-transform duration-500 group-hover:scale-110">
                            <svg class="w-6 h-6 text-[#637381]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div id="fileName" class="text-[13px] font-bold text-[#007BFF]">Pilih File KRS</div>
                        <div id="uploadText" class="text-[11px] text-[#637381] font-medium mt-1">Format PDF atau Gambar (Maks. 2MB)</div>
                    </label>
                    @error('krs_file')
                        <p class="text-[11px] text-red-500 mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full mt-4 bg-[#1C252E] hover:bg-black text-white font-extrabold py-4 rounded-[12px] shadow-[0_10px_20px_rgba(28,37,46,0.15)] transition-all transform active:scale-[0.98] text-[14px]">
                    Kirim Pengajuan Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const krsInput = document.getElementById('krsInput');
    const fileName = document.getElementById('fileName');
    const uploadIcon = document.getElementById('uploadIcon');
    const form = document.getElementById('proposalForm');
    const topicSelect = document.getElementById('topic_select');
    const dosenSelect = document.getElementById('dosen_select');

    // --- 0) LOGIK AUTO-LOAD DOSEN JIKA ADA TOPIC TERPILIH (Auto-Fill) ---
    async function loadDosens(topicId, selectedId = null) {
        if (!topicId) {
            dosenSelect.innerHTML = '<option value="">-- Pilih Topik Terlebih Dahulu --</option>';
            return;
        }

        dosenSelect.innerHTML = '<option value="">Memuat dosen...</option>';
        try {
            const url = `{{ route('student.dosens.byTopic') }}?topic_id=${topicId}`;
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();

            dosenSelect.innerHTML = '<option value="">Pilih Dosen Pembimbing</option>';
            data.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = `${d.name}${d.title ? ' (' + d.title + ')' : ''}`;
                if (selectedId && d.id == selectedId) {
                    opt.selected = true;
                }
                dosenSelect.appendChild(opt);
            });
        } catch (e) {
            dosenSelect.innerHTML = '<option value="">Gagal memuat data</option>';
        }
    }

    if (topicSelect.value) {
        const initialDosenId = "{{ $selectedDosen->id ?? '' }}";
        loadDosens(topicSelect.value, initialDosenId);
    }

    topicSelect.addEventListener('change', (e) => {
        loadDosens(e.target.value);
    });

    // --- 1) Semester otomatis ---
    const gradDate = document.getElementById('graduation_date');
    const gradEstimateHidden = document.getElementById('graduation_estimate');
    const gradEstimateDisplay = document.getElementById('graduation_estimate_display');

    function computeSemesterLabel(dateStr) {
        if (!dateStr) return "";
        const dt = new Date(dateStr);
        const bulan = dt.getMonth();
        const tahun = dt.getFullYear();
        const namaBulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

        if (bulan >= 7) {
            return `Semester Ganjil ${tahun} (${namaBulan[bulan]} ${tahun})`;
        } else if (bulan <= 1) {
            return `Semester Ganjil ${tahun - 1} (${namaBulan[bulan]} ${tahun})`;
        }

        return `Semester Genap ${tahun} (${namaBulan[bulan]} ${tahun})`;
    }

    function syncGraduationEstimate() {
        const label = computeSemesterLabel(gradDate.value);
        gradEstimateDisplay.value = label;
        gradEstimateHidden.value = label;
    }

    if (gradDate) {
        gradDate.addEventListener('change', syncGraduationEstimate);
        syncGraduationEstimate();
    }

    // --- 2) Animasi File Upload ---
    krsInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            fileName.textContent = this.files[0].name;
            fileName.classList.remove('text-[#007BFF]');
            fileName.classList.add('text-green-600');
            uploadIcon.innerHTML = `<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
        }
    });

    // --- 3) Alert Konfirmasi + validasi wajib isi ---
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const studentName = form.querySelector('[name="student_name"]');
        const studentId = form.querySelector('[name="student_id"]');
        const whatsapp = form.querySelector('[name="whatsapp"]');
        const title = form.querySelector('[name="title"]');
        const abstract = form.querySelector('[name="abstract"]');

        if (!studentName.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'Nama lengkap wajib diisi.', confirmButtonColor: '#1C252E' });
            return;
        }

        if (!studentId.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'NPM wajib diisi.', confirmButtonColor: '#1C252E' });
            return;
        }

        if (!whatsapp.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'Nomor WhatsApp wajib diisi.', confirmButtonColor: '#1C252E' });
            return;
        }

        if (!gradDate.value) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'Pilih tanggal estimasi lulus dahulu.', confirmButtonColor: '#1C252E' });
            return;
        }

        if (!gradEstimateHidden.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'Estimasi semester belum terisi.', confirmButtonColor: '#1C252E' });
            return;
        }

        if (!title.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'Judul skripsi wajib diisi.', confirmButtonColor: '#1C252E' });
            return;
        }

        if (!topicSelect.value) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'Pilih topik bidang minat terlebih dahulu.', confirmButtonColor: '#1C252E' });
            return;
        }

        if (!dosenSelect.value) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'Pilih dosen pembimbing terlebih dahulu.', confirmButtonColor: '#1C252E' });
            return;
        }

        if (!abstract.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'Alasan pengajuan wajib diisi.', confirmButtonColor: '#1C252E' });
            return;
        }

        if (!krsInput.files || !krsInput.files[0]) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'Upload bukti KRS aktif terlebih dahulu.', confirmButtonColor: '#1C252E' });
            return;
        }

        Swal.fire({
            title: 'Kirim Data?',
            text: "Pastikan semua informasi sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1C252E',
            confirmButtonText: 'Ya, Kirim!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Sedang Mengirim...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                form.submit();
            }
        });
    });
});
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Form Terkirim!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
    }).then(() => {
        window.location.href = "{{ route('student.dashboard') }}";
    });
</script>
@endif
@endsection