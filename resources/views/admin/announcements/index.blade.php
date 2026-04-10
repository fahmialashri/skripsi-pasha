@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-5xl mx-auto">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-[20px] font-extrabold text-[#1C252E]">Pengumuman</h1>
      <p class="text-[12px] text-[#637381] font-semibold mt-1">Kelola pengumuman untuk dashboard mahasiswa.</p>
    </div>

    <a href="{{ route('admin.announcements.create') }}"
      class="bg-[#1677FF] text-white px-4 py-2.5 rounded-[10px] font-extrabold text-[12px] shadow-sm hover:opacity-95">
      + Tambah Pengumuman
    </a>
  </div>

  @if(session('success'))
    <script>
      Swal.fire({ icon:'success', title:'Berhasil', text:@json(session('success')), timer:2000, showConfirmButton:false });
    </script>
  @endif

  {{-- Filter --}}
  <form method="GET" class="bg-white border border-[#EEF0F3] rounded-[14px] p-4 mb-5">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <input name="q" value="{{ $q ?? '' }}" placeholder="Cari judul..."
        class="rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-semibold" />

      <select name="category"
        class="rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-semibold">
        <option value="">Semua Kategori</option>
        @foreach($categories as $key => $label)
          <option value="{{ $key }}" @selected(($category ?? '') === $key)>{{ $label }}</option>
        @endforeach
      </select>

      <button class="bg-[#1C252E] hover:bg-black text-white rounded-[12px] px-4 py-3 text-[13px] font-extrabold">
        Terapkan
      </button>
    </div>
  </form>

  {{-- Table --}}
  <div class="bg-white border border-[#EEF0F3] rounded-[14px] overflow-hidden">
    <table class="w-full text-[12px]">
      <thead class="bg-[#F9FAFB] text-[#637381]">
        <tr>
          <th class="text-left p-4">Judul</th>
          <th class="text-left p-4">Kategori</th>
          <th class="text-left p-4">Diposting</th>
          <th class="text-left p-4">Aksi</th>
          <th class="text-left p-4">Status</th>
          <th class="text-right p-4">Menu</th>
        </tr>
      </thead>
      <tbody>
        @forelse($announcements as $a)
          @php
            $catLabel = $categories[$a->category] ?? strtoupper($a->category);
          @endphp
          <tr class="border-t border-[#EEF0F3]">
            <td class="p-4 font-bold text-[#1C252E]">{{ $a->title }}</td>
            <td class="p-4">
              <span class="px-3 py-1 rounded-full border text-[11px] font-extrabold bg-slate-50 text-slate-700 border-slate-200">
                {{ $catLabel }}
              </span>
            </td>
            <td class="p-4 text-[#637381] font-semibold">{{ $a->posted_by ?? 'Admin' }}</td>
            <td class="p-4 text-[#637381] font-semibold uppercase">
              {{ $a->action_type === 'download' ? 'UNDUH' : 'LIHAT' }}
            </td>
            <td class="p-4">
              <span class="px-3 py-1 rounded-full border text-[11px] font-extrabold
                {{ $a->is_active ? 'bg-[#EBFBEE] text-[#118D57] border-[#CFF7D6]' : 'bg-[#FFE8E8] text-[#B42318] border-[#FFD0D0]' }}">
                {{ $a->is_active ? 'AKTIF' : 'NONAKTIF' }}
              </span>
            </td>
            <td class="p-4 text-right">
              <a href="{{ route('admin.announcements.edit', $a) }}" class="text-[#1677FF] font-extrabold hover:underline">Edit</a>

              <form action="{{ route('admin.announcements.destroy', $a) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="button" class="ml-3 text-rose-600 font-extrabold hover:underline"
                  onclick="confirmDelete(this.form)">
                  Hapus
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="p-8 text-center text-[#637381] font-bold">Belum ada pengumuman.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-5">{{ $announcements->links() }}</div>
</div>

<script>
function confirmDelete(form){
  Swal.fire({
    icon:'warning',
    title:'Hapus pengumuman?',
    text:'Data akan dihapus permanen.',
    showCancelButton:true,
    confirmButtonColor:'#1C252E',
    cancelButtonColor:'#F44336',
    confirmButtonText:'Ya, Hapus',
    cancelButtonText:'Batal'
  }).then(r => { if(r.isConfirmed) form.submit(); });
}
</script>
@endsection