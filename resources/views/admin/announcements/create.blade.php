@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white border border-[#EEF0F3] rounded-[16px] p-8">
  <h1 class="text-[18px] font-extrabold text-[#1C252E] mb-6">Tambah Pengumuman</h1>

  <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div>
      <label class="text-[11px] font-extrabold text-[#637381]">Judul</label>
      <input name="title" value="{{ old('title') }}" required
        class="mt-2 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-semibold" />
      @error('title') <div class="text-[11px] text-red-500 mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="text-[11px] font-extrabold text-[#637381]">Kategori</label>
        <select name="category" required
          class="mt-2 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-semibold">
          @foreach($categories as $key => $label)
            <option value="{{ $key }}" @selected(old('category','informasi')==$key)>{{ $label }}</option>
          @endforeach
        </select>
        @error('category') <div class="text-[11px] text-red-500 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="text-[11px] font-extrabold text-[#637381]">Diposting oleh</label>
        <input name="posted_by" value="{{ old('posted_by','Admin Informatika') }}"
          class="mt-2 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-semibold" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="text-[11px] font-extrabold text-[#637381]">Tipe tombol</label>
        <select name="action_type" required
          class="mt-2 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-semibold">
          <option value="download" @selected(old('action_type')=='download')>UNDUH</option>
          <option value="view" @selected(old('action_type')=='view')>LIHAT</option>
        </select>
      </div>

      <label class="flex items-center gap-2 text-[12px] font-bold text-[#637381] mt-8">
        <input type="checkbox" name="is_active" value="1" checked class="rounded" />
        Aktifkan pengumuman
      </label>
    </div>

    <div>
      <label class="text-[11px] font-extrabold text-[#637381]">Upload PDF (opsional jika pakai link)</label>
      <input type="file" name="file" accept="application/pdf" class="mt-2 w-full" />
      @error('file') <div class="text-[11px] text-red-500 mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-[11px] font-extrabold text-[#637381]">Link Drive/Website (opsional jika upload file)</label>
      <input name="external_url" value="{{ old('external_url') }}" placeholder="https://..."
        class="mt-2 w-full rounded-[12px] border border-[#E6E8EC] bg-[#F9FAFB] px-4 py-3 text-[13px] font-semibold" />
      @error('external_url') <div class="text-[11px] text-red-500 mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="pt-4 flex justify-end gap-2">
      <a href="{{ route('admin.announcements.index') }}"
        class="px-4 py-2.5 rounded-[10px] text-[12px] font-extrabold text-[#637381] hover:bg-[#F4F6F8]">Batal</a>
      <button type="submit"
        class="px-5 py-2.5 rounded-[10px] bg-[#1C252E] text-white text-[12px] font-extrabold hover:bg-black">
        Simpan
      </button>
    </div>
  </form>
</div>
@endsection