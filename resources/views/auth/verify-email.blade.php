<x-guest-layout>
  <div class="min-h-screen flex items-center justify-center bg-[#F6F7FB] px-4 py-10">
    <div class="w-full max-w-[420px]">
      <div class="bg-white border border-[#EEF0F3] rounded-[16px] shadow-[0_10px_30px_rgba(0,0,0,0.05)] px-8 py-8">

        {{-- Icon top --}}
        <div class="flex justify-center mb-5">
          <div class="w-12 h-12 rounded-full bg-[#EEF0F3] flex items-center justify-center">
            <svg class="w-6 h-6 text-[#637381]" viewBox="0 0 24 24" fill="none">
              <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M22 6l-10 7L2 6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>

        <div class="text-center mb-6">
          <h1 class="text-[20px] font-extrabold text-[#1C252E]">Verifikasi Email</h1>
          <p class="text-[12px] text-[#637381] font-medium mt-1">
            Silakan cek email Anda dan klik link verifikasi untuk melanjutkan.
          </p>
        </div>

        <div class="text-[12px] text-[#637381] font-medium leading-relaxed bg-[#F9FAFB] border border-[#EEF0F3] rounded-[12px] px-4 py-3">
          Jika belum menerima email, Anda bisa kirim ulang link verifikasi dari tombol di bawah.
        </div>

        @if (session('status') == 'verification-link-sent')
          <div class="mt-4 text-[12px] font-extrabold text-green-700 bg-green-50 border border-green-200 rounded-[12px] px-4 py-3">
            Link verifikasi baru sudah dikirim ke email Anda.
          </div>
        @endif

        <div class="mt-6 flex items-center justify-between gap-3">
          {{-- Resend --}}
          <form method="POST" action="{{ route('verification.send') }}" class="w-full">
            @csrf
            <button
              type="submit"
              class="w-full bg-[#637381] hover:bg-[#4F5B66] text-white font-extrabold
                     py-3 rounded-[10px] shadow-sm transition text-[12px]"
            >
              Kirim Ulang Verifikasi
            </button>
          </form>

          {{-- Logout --}}
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
              type="submit"
              class="px-4 py-3 rounded-[10px] border border-[#EEF0F3] bg-white
                     text-[12px] font-extrabold text-[#637381] hover:bg-[#F4F6F8] transition"
            >
              Logout
            </button>
          </form>
        </div>

      </div>

      <div class="text-center text-[11px] text-[#919EAB] font-medium mt-5">
        Ada kendala? <span class="text-[#1677FF] font-extrabold">Hubungi Bagian Akademik</span>
      </div>
    </div>
  </div>
</x-guest-layout>