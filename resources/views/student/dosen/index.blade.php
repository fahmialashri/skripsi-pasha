@extends('layouts.app')

@php
  $pageTitle = 'Daftar Dosen';
  $topbarVariant = 'default';
@endphp

@section('content')
<div class="font-['Inter',_sans-serif] text-[#212B36]">

  <div class="flex items-start justify-between gap-4 mb-6">
    <div class="min-w-0">
      <h1 class="text-[18px] md:text-[22px] font-extrabold text-[#1C252E]">Daftar Dosen</h1>
      <p class="text-[12px] md:text-[13px] text-[#637381] font-medium mt-1">
        Lihat kuota dosen pembimbing (kuota terpakai dihitung dari status <b>Verified</b> saja).
      </p>
    </div>
  </div>

  {{-- FILTER BAR --}}
  <div class="bg-white border border-[#EEF0F3] rounded-[14px] p-4 mb-6 shadow-[0_4px_14px_rgba(0,0,0,0.04)]">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="text-[11px] font-extrabold text-[#637381]">Cari</label>
        <input
          name="q"
          value="{{ request('q') }}"
          placeholder="Cari nama Dosen..."
          class="mt-2 w-full rounded-[10px] border border-[#E6E8EC] bg-white px-3 py-2 text-[12px]
                 font-medium text-[#212B36] placeholder:text-[#919EAB]
                 focus:outline-none focus:ring-2 focus:ring-[#DCE6FF]"
        />
      </div>

      <div>
        <label class="text-[11px] font-extrabold text-[#637381]">Filter Bidang</label>
        <div class="mt-2 relative">
          <select
            name="topic_id"
            class="w-full appearance-none rounded-[10px] border border-[#E6E8EC] bg-white px-3 py-2 pr-9
                   text-[12px] font-medium text-[#212B36]
                   focus:outline-none focus:ring-2 focus:ring-[#DCE6FF]"
          >
            <option value="">Semua bidang</option>
            @foreach($topics as $t)
              <option value="{{ $t->id }}" @selected(request('topic_id') == $t->id)>{{ $t->name }}</option>
            @endforeach
          </select>

          <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[#637381]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </span>
        </div>
      </div>

      <div class="flex items-end gap-2">
        <button
          class="w-full md:w-auto px-4 py-2.5 rounded-[10px] bg-[#007BFF] text-white text-[12px] font-extrabold hover:opacity-90 transition"
          type="submit"
        >
          Terapkan
        </button>

        <a
          href="{{ route('student.dosen.index') }}"
          class="w-full md:w-auto px-4 py-2.5 rounded-[10px] bg-white border border-[#E6E8EC] text-[#212B36]
                 text-[12px] font-extrabold hover:bg-[#F4F6F8] transition text-center"
        >
          Reset
        </a>
      </div>
    </form>
  </div>

  {{-- GRID DOSEN --}}
  <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
    @forelse($dosens as $d)
      @php
        $quota = (int)($d->quota ?? 0);
        $used  = (int)($d->assigned_count ?? 0);
        $left  = max(0, $quota - $used);
        $isFull = $quota > 0 && $used >= $quota;
        $applyHref = route('student.proposal.create', ['selected_dosen_id' => $d->id]);
      @endphp

      <div class="bg-white border border-[#EEF0F3] rounded-[16px] p-4 md:p-5 shadow-[0_4px_14px_rgba(0,0,0,0.04)] flex flex-col">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <div class="text-[14px] md:text-[15px] font-extrabold text-[#1C252E] truncate">
              {{ $d->name }}
            </div>
            <div class="text-[11px] md:text-[12px] text-[#637381] font-bold mt-1 truncate">
              {{ $d->title ?? 'Dosen' }}
            </div>

            {{-- Badge + Kuota --}}
            <div class="mt-3 flex flex-wrap items-center gap-2" data-badge data-dosen-id="{{ $d->id }}">
              <span data-status
                class="px-2.5 py-1 rounded-[999px] text-[10px] font-extrabold
                       {{ $isFull ? 'bg-[#FFE8E8] text-[#B42318]' : 'bg-[#EBFBEE] text-[#118D57]' }}">
                {{ $isFull ? 'Penuh' : 'Tersedia' }}
              </span>

              <span data-quota class="text-[10px] md:text-[11px] font-bold text-[#919EAB]">
                Kuota: <span class="text-[#212B36]">{{ $left }}</span>/{{ $quota }}
              </span>
            </div>
          </div>

          <div class="w-9 h-9 md:w-10 md:h-10 rounded-[12px] bg-[#EEF4FF] flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-[#007BFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a3.5 3.5 0 0 0-2.5-3.35"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.65a4 4 0 0 1 0 7.7"/>
            </svg>
          </div>
        </div>

        <div class="mt-4 flex-1">
          <div class="text-[10px] md:text-[11px] font-extrabold text-[#637381] mb-2">Expertise</div>
          <div class="text-[11px] md:text-[12px] font-medium text-[#212B36] leading-relaxed line-clamp-3">
            {{ $d->expertise ?? '-' }}
          </div>
        </div>

        <div class="mt-5 flex gap-2">
          <a
            data-ajukan
            data-href="{{ $applyHref }}"
            data-dosen-id="{{ $d->id }}"
            href="{{ $isFull ? '#' : $applyHref }}"
            class="flex-1 text-center font-extrabold text-[12px] py-2.5 rounded-[10px] transition
                   {{ $isFull ? 'bg-[#E6E8EC] text-[#919EAB] cursor-not-allowed' : 'bg-[#007BFF] hover:opacity-90 text-white' }}"
            @if($isFull) aria-disabled="true" onclick="return false;" @endif
          >
            Ajukan
          </a>
        </div>
      </div>
    @empty
      {{-- ALERT SAAT DOSEN TIDAK ADA --}}
      <div class="col-span-full py-16 flex flex-col items-center justify-center bg-white border border-dashed border-[#EEF0F3] rounded-[20px]">
        <div class="w-16 h-16 bg-[#F9FAFB] rounded-full flex items-center justify-center mb-4">
          <svg class="w-8 h-8 text-[#919EAB]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <h3 class="text-[16px] font-extrabold text-[#1C252E]">Dosen tidak ditemukan</h3>
        <p class="text-[12px] text-[#637381] mt-2 text-center max-w-[300px] leading-relaxed">
            Maaf, kami tidak menemukan dosen dengan nama 
            <span class="text-[#1C252E] font-bold">"{{ request('q') }}"</span>. 
            Silakan coba kata kunci lain atau pilih bidang yang berbeda.
        </p>
        <a href="{{ route('student.dosen.index') }}" class="mt-6 px-6 py-2 rounded-[10px] bg-[#EEF4FF] text-[#007BFF] text-[12px] font-extrabold hover:bg-[#007BFF] hover:text-white transition">
          Reset Pencarian
        </a>
      </div>
    @endforelse
  </div>

  <div class="mt-6">
    {{ $dosens->links() }}
  </div>

</div>
@endsection

@push('scripts')
<script>
  async function refreshAvailability() {
    const wraps = document.querySelectorAll('[data-badge][data-dosen-id]');
    const ids = Array.from(wraps).map(x => x.getAttribute('data-dosen-id'));

    if (!ids.length) return;

    const url = `{{ route('student.dosen.availability') }}?` + new URLSearchParams({ ids });

    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
    if (!res.ok) return;

    const data = await res.json();

    data.forEach(item => {
      const wrap = document.querySelector(`[data-badge][data-dosen-id="${item.id}"]`);
      const btn  = document.querySelector(`[data-ajukan][data-dosen-id="${item.id}"]`);
      if (!wrap) return;

      const statusEl = wrap.querySelector('[data-status]');
      const quotaEl  = wrap.querySelector('[data-quota]');

      if (statusEl) {
        statusEl.textContent = item.isFull ? 'Penuh' : 'Tersedia';
        statusEl.className =
          'px-2.5 py-1 rounded-[999px] text-[10px] font-extrabold ' +
          (item.isFull ? 'bg-[#FFE8E8] text-[#B42318]' : 'bg-[#EBFBEE] text-[#118D57]');
      }

      if (quotaEl) {
        quotaEl.innerHTML = 'Kuota: <span class="text-[#212B36]">' + item.left + '</span>/' + item.quota;
      }

      if (btn) {
        const originalHref = btn.getAttribute('data-href') || '#';
        if (item.isFull) {
          btn.setAttribute('href', '#');
          btn.className = 'flex-1 text-center font-extrabold text-[12px] py-2.5 rounded-[10px] transition bg-[#E6E8EC] text-[#919EAB] cursor-not-allowed';
          btn.onclick = () => false;
        } else {
          btn.setAttribute('href', originalHref);
          btn.className = 'flex-1 text-center font-extrabold text-[12px] py-2.5 rounded-[10px] transition bg-[#007BFF] hover:opacity-90 text-white';
          btn.onclick = null;
        }
      }
    });
  }

  refreshAvailability();
  setInterval(refreshAvailability, 5000);
</script>
@endpush