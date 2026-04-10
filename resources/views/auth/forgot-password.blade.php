<x-guest-layout>
  <div class="min-h-screen flex items-center justify-center bg-[#F6F7FB] px-4 py-10">
    <div class="w-full max-w-[380px]">
      <div class="bg-white border border-[#EEF0F3] rounded-[16px] shadow-[0_10px_30px_rgba(0,0,0,0.05)] px-8 py-8">

        {{-- Icon top --}}
        <div class="flex justify-center mb-5">
          <div class="w-12 h-12 rounded-full bg-[#EEF0F3] flex items-center justify-center">
            <svg class="w-6 h-6 text-[#637381]" viewBox="0 0 24 24" fill="none">
              <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>

        <div class="text-center mb-6">
          <h1 class="text-[20px] font-extrabold text-[#1C252E]">Lupa Password</h1>
          <p class="text-[12px] text-[#637381] font-medium mt-1">
            Masukkan email untuk menerima link reset password.
          </p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
          @csrf

          {{-- Email --}}
          <div>
            <label for="email" class="block text-[10px] font-black tracking-widest text-[#919EAB] uppercase mb-2">
              Email
            </label>

            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#919EAB]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                  <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
              </span>

              <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                placeholder="Masukkan email terdaftar"
                class="w-full rounded-[10px] border border-[#E6E8EC] bg-white pl-10 pr-3 py-2.5
                       text-[12px] font-medium text-[#212B36] placeholder:text-[#919EAB]
                       focus:outline-none focus:ring-2 focus:ring-[#DCE6FF]"
              />
            </div>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          <button
            type="submit"
            class="w-full mt-2 bg-[#637381] hover:bg-[#4F5B66] text-white font-extrabold
                   py-3 rounded-[10px] shadow-sm transition text-[12px]"
          >
            Kirim Link Reset Password
          </button>

          <div class="pt-4 border-t border-[#EEF0F3] text-center">
            <a href="{{ route('login') }}" class="text-[12px] text-[#1677FF] font-extrabold hover:underline">
              Kembali ke Login
            </a>
          </div>
        </form>

      </div>

      <div class="text-center text-[11px] text-[#919EAB] font-medium mt-5">
        Ada kendala? <span class="text-[#1677FF] font-extrabold">Hubungi Bagian Akademik</span>
      </div>
    </div>
  </div>
</x-guest-layout>