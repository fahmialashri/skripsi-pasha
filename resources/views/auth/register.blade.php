<x-guest-layout>
  <div class="min-h-screen flex items-center justify-center bg-[#F6F7FB] px-4 py-10">
    <div class="w-full max-w-[380px]">
      <div class="bg-white border border-[#EEF0F3] rounded-[16px] shadow-[0_10px_30px_rgba(0,0,0,0.05)] px-8 py-8">

        {{-- Icon top --}}
         <div class="flex justify-center mb-5">
    <div class="w-20 h-20 flex items-center justify-center">
        <img 
            src="{{ asset('images/logo-fasilkom.jpeg') }}" 
            alt="Logo Fasilkom" 
            class="w-full h-full object-contain"
        >
    </div>
</div>

        <div class="text-center mb-6">
          <h1 class="text-[20px] font-extrabold text-[#1C252E]">Registrasi Mahasiswa</h1>
          <p class="text-[12px] text-[#637381] font-medium mt-1">Sistem Pengajuan Dosen Pembimbing</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
          @csrf

          {{-- Nama --}}
          <div>
            <label for="name" class="block text-[10px] font-black tracking-widest text-[#919EAB] uppercase mb-2">
              Nama Lengkap
            </label>

            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#919EAB]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                </svg>
              </span>

              <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Masukkan nama sesuai KTM"
                class="w-full rounded-[10px] border border-[#E6E8EC] bg-white pl-10 pr-3 py-2.5
                       text-[12px] font-medium text-[#212B36] placeholder:text-[#919EAB]
                       focus:outline-none focus:ring-2 focus:ring-[#DCE6FF]"
              />
            </div>

            <x-input-error :messages="$errors->get('name')" class="mt-2" />
          </div>

          {{-- NPM -> student_id --}}
          <div>
            <label for="student_id" class="block text-[10px] font-black tracking-widest text-[#919EAB] uppercase mb-2">
              NPM
            </label>

            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#919EAB]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                  <path d="M7 7h10M7 12h10M7 17h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M6 3h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2"/>
                </svg>
              </span>

              <input
                id="student_id"
                name="student_id"
                type="text"
                value="{{ old('student_id') }}"
                required
                autocomplete="username"
                placeholder="Masukkan NPM Anda"
                class="w-full rounded-[10px] border border-[#E6E8EC] bg-white pl-10 pr-3 py-2.5
                       text-[12px] font-medium text-[#212B36] placeholder:text-[#919EAB]
                       focus:outline-none focus:ring-2 focus:ring-[#DCE6FF]"
              />
            </div>

            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
          </div>

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
                autocomplete="email"
                placeholder="Masukkan Email Mahasiswa"
                class="w-full rounded-[10px] border border-[#E6E8EC] bg-white pl-10 pr-3 py-2.5
                       text-[12px] font-medium text-[#212B36] placeholder:text-[#919EAB]
                       focus:outline-none focus:ring-2 focus:ring-[#DCE6FF]"
              />
            </div>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          {{-- Password + Konfirmasi --}}
          <div class="grid grid-cols-2 gap-4">
            {{-- Password --}}
            <div>
              <label for="password" class="block text-[10px] font-black tracking-widest text-[#919EAB] uppercase mb-2">
                Password
              </label>

              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#919EAB]">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                    <path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M6 11h12v10H6V11Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  </svg>
                </span>

                <input
                  id="password"
                  name="password"
                  type="password"
                  required
                  autocomplete="new-password"
                  placeholder="••••••••"
                  class="w-full rounded-[10px] border border-[#E6E8EC] bg-white pl-10 pr-10 py-2.5
                         text-[12px] font-medium text-[#212B36] placeholder:text-[#919EAB]
                         focus:outline-none focus:ring-2 focus:ring-[#DCE6FF]"
                />

                {{-- Icon Mata --}}
                <button type="button"
                        onclick="togglePassword('password', 'eyeOpenPw', 'eyeClosedPw')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#919EAB] hover:text-[#1C252E] transition">
                  <svg id="eyeOpenPw" class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z" stroke="currentColor" stroke-width="2"/>
                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                  </svg>
                  <svg id="eyeClosedPw" class="w-4 h-4 hidden" viewBox="0 0 24 24" fill="none">
                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94M9.88 9.88A3 3 0 1 0 14.12 14.12" stroke="currentColor" stroke-width="2"/>
                    <line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="2"/>
                  </svg>
                </button>
              </div>

              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Konfirmasi --}}
            <div>
              <label for="password_confirmation" class="block text-[10px] font-black tracking-widest text-[#919EAB] uppercase mb-2">
                Konfirmasi
              </label>

              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#919EAB]">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                    <path d="M8 12h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M6 11h12v10H6V11Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  </svg>
                </span>

                <input
                  id="password_confirmation"
                  name="password_confirmation"
                  type="password"
                  required
                  autocomplete="new-password"
                  placeholder="••••••••"
                  class="w-full rounded-[10px] border border-[#E6E8EC] bg-white pl-10 pr-10 py-2.5
                         text-[12px] font-medium text-[#212B36] placeholder:text-[#919EAB]
                         focus:outline-none focus:ring-2 focus:ring-[#DCE6FF]"
                />

                {{-- Icon Mata --}}
                <button type="button"
                        onclick="togglePassword('password_confirmation', 'eyeOpenConf', 'eyeClosedConf')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#919EAB] hover:text-[#1C252E] transition">
                  <svg id="eyeOpenConf" class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z" stroke="currentColor" stroke-width="2"/>
                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                  </svg>
                  <svg id="eyeClosedConf" class="w-4 h-4 hidden" viewBox="0 0 24 24" fill="none">
                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94M9.88 9.88A3 3 0 1 0 14.12 14.12" stroke="currentColor" stroke-width="2"/>
                    <line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="2"/>
                  </svg>
                </button>
              </div>

              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
          </div>

          <button
            type="submit"
            class="w-full mt-2 bg-[#637381] hover:bg-[#4F5B66] text-white font-extrabold
                   py-3 rounded-[10px] shadow-sm transition text-[12px]"
          >
            Daftar
          </button>

          <div class="pt-4 border-t border-[#EEF0F3] text-center">
            <div class="text-[12px] text-[#637381] font-medium">
              Sudah punya akun?
              <a href="{{ route('login') }}" class="text-[#1677FF] font-extrabold hover:underline">Masuk</a>
            </div>
          </div>
        </form>
      </div>

      <div class="text-center text-[11px] text-[#919EAB] font-medium mt-5">
    Ada kendala? 
    <a href="https://wa.me/6287896314494" target="_blank" class="text-[#1677FF] font-extrabold hover:underline">
        Hubungi Bagian Akademik
    </a>
</div>
    </div>
  </div>

  {{-- Script Toggle Password (Reusable) --}}
  <script>
    function togglePassword(inputId, eyeOpenId, eyeClosedId) {
      const input = document.getElementById(inputId);
      const eyeOpen = document.getElementById(eyeOpenId);
      const eyeClosed = document.getElementById(eyeClosedId);

      if (!input || !eyeOpen || !eyeClosed) return;

      if (input.type === "password") {
        input.type = "text";
        eyeOpen.classList.add("hidden");
        eyeClosed.classList.remove("hidden");
      } else {
        input.type = "password";
        eyeOpen.classList.remove("hidden");
        eyeClosed.classList.add("hidden");
      }
    }
  </script>
</x-guest-layout>