@extends('layouts.app')

@php
  $topbarVariant = 'profile';
  $profileTab = 'profil';
@endphp

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="font-['Inter',_sans-serif] text-[#212B36]">

  {{-- CARD PROFIL --}}
  <div class="bg-white border border-[#EEF0F3] rounded-[16px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] p-8">
    <div class="flex flex-col md:flex-row gap-8">

      {{-- Avatar box --}}
      <div class="w-[160px]">
        <div class="w-[140px] h-[140px] rounded-[16px] bg-[#F4F6F8] border border-[#EEF0F3] flex items-center justify-center">
          <svg class="w-14 h-14 text-[#919EAB]" viewBox="0 0 24 24" fill="none">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
          </svg>
        </div>

        {{-- Button edit --}}
        <button type="button" id="btnOpenEdit"
          class="mt-4 w-[140px] bg-[#1677FF] hover:opacity-95 text-white px-4 py-3 rounded-[12px]
                 font-extrabold text-[12px] shadow-sm transition">
          Edit Profil
        </button>
      </div>

      {{-- Detail --}}
      <div class="flex-1">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-6">

          <div>
            <div class="text-[10px] font-extrabold tracking-widest text-[#919EAB] uppercase">Nama Lengkap</div>
            <div class="mt-2 text-[16px] font-extrabold text-[#1C252E]">{{ $user->name }}</div>
          </div>

          <div>
            <div class="text-[10px] font-extrabold tracking-widest text-[#919EAB] uppercase">Nomor Pokok Mahasiswa (NPM)</div>
            <div class="mt-2 text-[16px] font-extrabold text-[#1C252E]">{{ $user->student_id ?? '-' }}</div>
          </div>

          <div>
            <div class="text-[10px] font-extrabold tracking-widest text-[#919EAB] uppercase">Email Mahasiswa</div>
            <div class="mt-2 text-[16px] font-extrabold text-[#1C252E]">{{ $user->email ?? '-' }}</div>
          </div>

          <div>
            <div class="text-[10px] font-extrabold tracking-widest text-[#919EAB] uppercase">No. Handphone</div>
            <div class="mt-2 text-[16px] font-extrabold text-[#1C252E]">{{ $phone ?? '-' }}</div>
          </div>

          <div>
            <div class="text-[10px] font-extrabold tracking-widest text-[#919EAB] uppercase">Angkatan</div>
            <div class="mt-2 text-[16px] font-extrabold text-[#1C252E]">{{ $angkatan ?? '-' }}</div>
          </div>

          <div>
            <div class="text-[10px] font-extrabold tracking-widest text-[#919EAB] uppercase">Program Studi</div>
            <div class="mt-2 text-[16px] font-extrabold text-[#1C252E]">{{ $prodi ?? '-' }}</div>
          </div>

        </div>
      </div>

    </div>
  </div>

  {{-- RIWAYAT + CTA --}}
  <div class="flex items-center justify-between mt-10 mb-5">
    <h2 class="text-[22px] font-extrabold text-[#1C252E]">Riwayat Pengisian</h2>

    <a href="{{ route('student.proposal.create') }}"
       class="bg-[#1C252E] hover:bg-black text-white px-5 py-3 rounded-[12px]
              font-extrabold text-[12px] flex items-center gap-2 shadow-sm transition">
      <span class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center text-[14px] leading-none">+</span>
      Ajukan Pembimbing
    </a>
  </div>

  {{-- LIST RIWAYAT --}}
  <div class="space-y-4">
    @forelse($proposals as $p)
      @php
        $badgeText = match($p->status) {
            'verified' => 'DISETUJUI',
            'pending'  => 'MENUNGGU',
            'rejected' => 'DITOLAK',
            default    => strtoupper($p->status),
        };

        $badgeClass = match($p->status) {
            'verified' => 'bg-[#EBFBEE] text-[#118D57] border-[#CFF7D6]',
            'pending'  => 'bg-[#FFF9E6] text-[#B78103] border-[#FFEBB3]',
            'rejected' => 'bg-[#FFE8E8] text-[#B42318] border-[#FFD0D0]',
            default    => 'bg-slate-100 text-slate-600 border-slate-200',
        };

        $dosenName = $p->selectedDosen ? ($p->selectedDosen->name . ', ' . ($p->selectedDosen->title ?? '')) : '-';
        $dateText = $p->created_at ? $p->created_at->translatedFormat('d M Y') : '-';
      @endphp

      <div class="bg-white border border-[#EEF0F3] rounded-[14px]
                  shadow-[0_10px_30px_rgba(0,0,0,0.03)]
                  px-6 py-5">
        <div class="flex items-start justify-between gap-4">
          <div class="min-w-0 flex-1">
            <div class="text-[16px] font-extrabold text-[#1C252E] leading-snug truncate">
              {{ $p->title }}
            </div>

            <div class="mt-2 flex items-center gap-4 text-[12px] text-[#637381] font-bold flex-wrap">
              <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#919EAB]" viewBox="0 0 24 24" fill="none">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>{{ $dosenName }}</span>
              </div>

              <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#919EAB]" viewBox="0 0 24 24" fill="none">
                  <path d="M8 7V3m8 4V3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M3 10h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M5 6h14a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
                <span>{{ $dateText }}</span>
              </div>
            </div>
          </div>

          <span class="px-4 py-2 rounded-[999px] text-[11px] font-extrabold tracking-wide border {{ $badgeClass }}">
            {{ $badgeText }}
          </span>
        </div>
      </div>
    @empty
      <div class="bg-white border border-[#EEF0F3] rounded-[14px] p-10 text-center
                  text-[12px] font-bold text-[#637381]">
        Belum ada riwayat pengisian.
      </div>
    @endforelse
  </div>

</div>

{{-- MODAL EDIT PROFILE (HANYA NAMA) --}}
<div id="editModal" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/40"></div>

  <div class="absolute inset-0 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-[520px] bg-white rounded-[16px] border border-[#EEF0F3]
                shadow-[0_20px_60px_rgba(0,0,0,0.20)] overflow-hidden">
      <div class="px-6 py-5 border-b border-[#EEF0F3] flex items-center justify-between">
        <div>
          <div class="text-[14px] font-extrabold text-[#1C252E]">Edit Profil</div>
          <div class="text-[11px] text-[#637381] font-semibold mt-0.5">Ubah nama profil Anda.</div>
        </div>

        <button type="button" id="btnCloseEdit"
          class="w-9 h-9 rounded-full bg-[#F4F6F8] hover:bg-[#EEF0F3] transition flex items-center justify-center">
          <svg class="w-4 h-4 text-[#637381]" viewBox="0 0 24 24" fill="none">
            <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <form id="editProfileForm" method="POST" action="{{ route('student.profile.update') }}" class="px-6 py-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
          <label class="block text-[10px] font-black tracking-widest text-[#919EAB] uppercase mb-2">Nama Lengkap</label>
          <input name="name" value="{{ old('name', $user->name) }}" required
            class="w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-semibold text-[#212B36]
                   focus:bg-white focus:ring-2 focus:ring-[#DCE6FF] focus:border-[#007BFF] outline-none transition-all" />
          @error('name') <div class="text-[11px] text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="pt-4 border-t border-[#EEF0F3] flex items-center justify-end gap-2">
          <button type="button" id="btnCancelEdit"
            class="px-4 py-2.5 rounded-[10px] text-[12px] font-extrabold text-[#637381] hover:bg-[#F4F6F8] transition">
            Batal
          </button>

          <button type="submit"
            class="px-5 py-2.5 rounded-[10px] bg-[#1C252E] text-white text-[12px] font-extrabold
                   shadow-[0_10px_18px_rgba(28,37,46,0.18)] hover:bg-black transition">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- SWEETALERT + MODAL LOGIC --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('editModal');
  const openBtn = document.getElementById('btnOpenEdit');
  const closeBtn = document.getElementById('btnCloseEdit');
  const cancelBtn = document.getElementById('btnCancelEdit');
  const form = document.getElementById('editProfileForm');

  function openModal(){ modal.classList.remove('hidden'); }
  function closeModal(){ modal.classList.add('hidden'); }

  openBtn?.addEventListener('click', openModal);
  closeBtn?.addEventListener('click', closeModal);
  cancelBtn?.addEventListener('click', closeModal);

  // Submit confirmation
  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    Swal.fire({
      icon: 'question',
      title: 'Simpan Perubahan?',
      text: 'Nama Anda akan diperbarui.',
      showCancelButton: true,
      confirmButtonColor: '#1C252E',
      cancelButtonColor: '#F44336',
      confirmButtonText: 'Ya, Simpan',
      cancelButtonText: 'Batal'
    }).then((res) => {
      if (res.isConfirmed) {
        Swal.fire({
          title: 'Menyimpan...',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });
        form.submit();
      }
    });
  });

  // Toast success dari controller
  @if(session('profile_updated'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: @json(session('profile_updated')),
      timer: 2500,
      timerProgressBar: true,
      showConfirmButton: false
    });
  @endif

  // Jika ada error validasi, buka modal otomatis
  @if($errors->any())
    openModal();
  @endif
});
</script>
@endsection