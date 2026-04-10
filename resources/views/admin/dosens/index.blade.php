@extends('layouts.app')

@section('content')
<div class="font-['Inter',_sans-serif] text-[#212B36]">

  {{-- HEADER SECTION --}}
  <div class="flex items-start justify-between gap-4 mb-6">
    <div>
      <h1 class="text-[18px] md:text-[22px] font-extrabold text-[#1C252E]">Kelola Dosen</h1>
      <p class="text-[12px] md:text-[13px] text-[#637381] font-medium mt-1">
        Atur <b>kuota</b> dan <b>topik/expertise</b> dosen. Kuota terpakai dihitung dari status <b class="text-[#007BFF]">Verified</b> saja.
      </p>
    </div>
  </div>

  {{-- ALERT SECTION (Hanya Alert yang pakai animasi agar terlihat interaktif) --}}
  <div class="space-y-3 mb-6">
    @if(session('success'))
      <div id="alert-success" class="flex items-center gap-3 bg-[#EBFBEE] text-[#118D57] border border-[#CFF7D6] px-4 py-3.5 rounded-[14px] shadow-sm animate__animated animate__fadeInDown">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="text-[12px] font-extrabold tracking-tight">{{ session('success') }}</div>
      </div>
    @endif

    @if ($errors->any())
      <div id="alert-error" class="flex items-start gap-3 bg-[#FFE8E8] text-[#B42318] border border-[#FFD0D0] px-4 py-3.5 rounded-[14px] shadow-sm animate__animated animate__shakeX">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
          <div class="text-[12px] font-extrabold tracking-tight mb-1">Terjadi Kesalahan:</div>
          <ul class="list-disc list-inside text-[11px] font-bold opacity-90 space-y-0.5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif
  </div>

  {{-- CARD TAMBAH DOSEN --}}
  <div class="bg-white border border-[#EEF0F3] rounded-[16px] p-6 mb-8 shadow-sm">
    <div class="text-[14px] font-black text-[#1C252E] mb-4 text-center md:text-left">Tambah Dosen Baru</div>
    <form method="POST" action="{{ route('admin.dosens.store') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
      @csrf
      <div class="md:col-span-4">
        <label class="text-[11px] font-extrabold text-[#637381] uppercase tracking-wider ml-1">Nama Lengkap</label>
        <input name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" class="mt-2 w-full rounded-[10px] border border-[#E6E8EC] px-3 py-2 text-[12px] font-bold focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all" />
      </div>
      <div class="md:col-span-2">
        <label class="text-[11px] font-extrabold text-[#637381] uppercase tracking-wider ml-1">Gelar</label>
        <input name="title" value="{{ old('title') }}" placeholder="M.Kom." class="mt-2 w-full rounded-[10px] border border-[#E6E8EC] px-3 py-2 text-[12px] font-bold focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all" />
      </div>
      <div class="md:col-span-2">
        <label class="text-[11px] font-extrabold text-[#637381] uppercase tracking-wider ml-1">Kuota</label>
        <input type="number" min="0" name="quota" value="{{ old('quota', 7) }}" class="mt-2 w-full rounded-[10px] border border-[#E6E8EC] px-3 py-2 text-[12px] font-black focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all" />
      </div>
      <div class="md:col-span-4">
        <label class="text-[11px] font-extrabold text-[#637381] uppercase tracking-wider ml-1">Expertise</label>
        <input name="expertise" value="{{ old('expertise') }}" placeholder="Software Engineering; AI" class="mt-2 w-full rounded-[10px] border border-[#E6E8EC] px-3 py-2 text-[12px] font-bold focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all" />
      </div>
      <div class="md:col-span-12 flex justify-end mt-2">
        <button type="submit" class="px-6 py-2.5 rounded-[12px] bg-[#1C252E] text-white text-[12px] font-black hover:bg-black transition-all active:scale-95 shadow-md shadow-slate-200">
          Simpan Dosen
        </button>
      </div>
    </form>
  </div>

  {{-- SEARCH BOX --}}
  <div class="bg-white border border-[#EEF0F3] rounded-[16px] p-4 mb-6 shadow-sm flex flex-col md:flex-row gap-3 md:items-end">
    <div class="flex-1">
      <label class="text-[11px] font-extrabold text-[#637381] uppercase tracking-wider ml-1">Cari Dosen</label>
      <input name="q" value="{{ request('q') }}" form="search-form" placeholder="Nama, gelar, atau bidang keahlian..." class="mt-2 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-2 text-[12px] font-medium focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all" />
    </div>
    <form id="search-form" method="GET" class="flex gap-2 w-full md:w-auto">
      <button type="submit" class="flex-1 md:flex-none px-6 py-2.5 rounded-[12px] bg-[#007BFF] text-white text-[12px] font-black hover:opacity-90 transition-all shadow-md shadow-blue-100">
        Filter
      </button>
      <a href="{{ route('admin.dosens.index') }}" class="flex-1 md:flex-none px-6 py-2.5 rounded-[12px] bg-white border border-[#E6E8EC] text-[#1C252E] text-[12px] font-black hover:bg-[#F4F6F8] transition-all text-center">
        Reset
      </a>
    </form>
  </div>

  {{-- TABLE LIST DOSEN --}}
  <div class="bg-white border border-[#EEF0F3] rounded-[14px] shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-[#EEF0F3] flex items-center justify-between bg-[#FBFCFE]">
      <div class="text-[13px] font-extrabold text-[#1C252E]">Daftar Dosen</div>
      <div class="px-3 py-1 bg-white border border-[#EEF0F3] rounded-full text-[11px] font-bold text-[#637381]">
        Total: {{ $dosens->total() }}
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-[#FBFCFE] border-b border-[#EEF0F3]">
          <tr class="text-[11px] font-black uppercase tracking-widest text-[#919EAB]">
            <th class="px-5 py-3">Dosen</th>
            <th class="px-5 py-3">Kuota</th>
            <th class="px-5 py-3">Expertise</th>
            <th class="px-5 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#EEF0F3]">
          @forelse($dosens as $d)
            @php
              $quota = (int)($d->quota ?? 0);
              $used  = (int)($d->assigned_count ?? 0); 
              $left  = max(0, $quota - $used);
              $full  = $quota > 0 && $used >= $quota;
            @endphp
            <tr class="hover:bg-[#F9FAFB] transition-colors">
              <td class="px-5 py-4">
                <div class="font-extrabold text-[13px] text-[#1C252E]">{{ $d->name }}</div>
                <div class="text-[12px] text-[#637381] font-bold mt-0.5">{{ $d->title ?? 'Dosen' }}</div>
                <div class="mt-2 flex items-center gap-2">
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $full ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                    {{ $full ? 'Penuh' : 'Tersedia' }}
                  </span>
                  <span class="text-[11px] font-bold text-[#919EAB]">Sisa: {{ $left }}/{{ $quota }}</span>
                </div>
              </td>

              <td class="px-5 py-4 w-[120px]">
                <form id="form-{{ $d->id }}" method="POST" action="{{ route('admin.dosens.update', $d) }}">
                  @csrf @method('PUT')
                  <input type="number" name="quota" value="{{ old('quota_'.$d->id, $d->quota) }}" class="w-full rounded-lg border border-[#E6E8EC] px-2 py-1.5 text-[12px] font-black focus:ring-2 focus:ring-[#DCE6FF] outline-none" />
                  <input type="hidden" name="expertise" value="{{ old('expertise_'.$d->id, $d->expertise) }}" data-expertise-hidden="{{ $d->id }}"/>
                  <input type="hidden" name="name" value="{{ $d->name }}"/>
                  <input type="hidden" name="title" value="{{ $d->title }}"/>
                </form>
              </td>

              <td class="px-5 py-4">
                <input type="text" value="{{ old('expertise_'.$d->id, $d->expertise) }}" placeholder="Expertise..." 
                  class="w-full rounded-lg border border-[#E6E8EC] px-3 py-1.5 text-[12px] font-medium focus:ring-2 focus:ring-[#DCE6FF] outline-none transition-all"
                  oninput="document.querySelector('[data-expertise-hidden={{ $d->id }}]').value=this.value" />
              </td>

              <td class="px-5 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <button type="submit" form="form-{{ $d->id }}" class="px-4 py-2 rounded-lg bg-[#007BFF] text-white text-[11px] font-black hover:opacity-90 transition shadow-sm active:scale-95">Simpan</button>
                  <form method="POST" action="{{ route('admin.dosens.destroy', $d) }}" onsubmit="return confirm('Hapus {{ $d->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-white border border-[#E6E8EC] text-rose-600 text-[11px] font-black hover:bg-rose-50 transition active:scale-95">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="py-10 text-center text-[#637381] italic">Tidak ada data dosen.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="px-5 py-4 border-t border-[#EEF0F3] bg-[#F9FAFB]">
      {{ $dosens->links() }}
    </div>
  </div>
</div>

<script>
    setTimeout(function() {
        const alerts = document.querySelectorAll('.animate__animated');
        alerts.forEach(alert => {
            alert.style.transition = "all 0.6s ease";
            alert.style.opacity = "0";
            alert.style.transform = "translateY(-10px)";
            setTimeout(() => alert.remove(), 600);
        });
    }, 4000);
</script>
@endsection