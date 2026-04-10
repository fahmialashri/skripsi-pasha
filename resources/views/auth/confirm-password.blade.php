<x-guest-layout>
  <div class="min-h-screen flex items-center justify-center bg-[#F6F7FB] px-4 py-10">
    <div class="w-full max-w-[380px]">
      <div class="bg-white border border-[#EEF0F3] rounded-[16px] shadow-[0_10px_30px_rgba(0,0,0,0.05)] px-8 py-8">

        {{-- Icon top --}}
        <div class="flex justify-center mb-5">
          <div class="w-12 h-12 rounded-full bg-[#EEF0F3] flex items-center justify-center">
            <svg class="w-6 h-6 text-[#637381]" viewBox="0 0 24 24" fill="none">
              <path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M6 11h12v10H6V11Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>

        <div class="text-center mb-6">
          <h1 class="text-[20px] font-extrabold text-[#1C252E]">Konfirmasi Password</h1>
          <p class="text-[12px] text-[#637381] font-medium mt-1">
            Area ini aman. Silakan masukkan password Anda untuk melanjutkan.
          </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
          @csrf

          {{-- Password --}}
          <div>
            <label for="password" class="block text-[10px] font-black tracking-widest text-[#919EAB] uppercase mb-2">
              Password
            </label>

            <div class="relative">
              {{-- Icon Lock --}}
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
                autocomplete="current-password"
                placeholder="Masukkan password Anda"
                class="w-full rounded-[10px] border border-[#E6E8EC] bg-white pl-10 pr-10 py-2.5
                       text-[12px] font-medium text-[#212B36] placeholder:text-[#919EAB]
                       focus:outline-none focus:ring-2 focus:ring-[#DCE6FF]"
              />

              {{-- Icon Mata --}}
              <button type="button"
                      onclick="togglePassword()"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-[#919EAB] hover:text-[#1C252E] transition">

                <svg id="eyeOpen" class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                  <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z" stroke="currentColor" stroke-width="2"/>
                  <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                </svg>

                <svg id="eyeClosed" class="w-4 h-4 hidden" viewBox="0 0 24 24" fill="none">
                  <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94M9.88 9.88A3 3 0 1 0 14.12 14.12" stroke="currentColor" stroke-width="2"/>
                  <line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="2"/>
                </svg>
              </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          <button
            type="submit"
            class="w-full mt-2 bg-[#637381] hover:bg-[#4F5B66] text-white font-extrabold
                   py-3 rounded-[10px] shadow-sm transition text-[12px]"
          >
            Konfirmasi
          </button>

          <div class="pt-4 border-t border-[#EEF0F3] text-center">
            <a href="{{ route('dashboard') }}" class="text-[12px] text-[#1677FF] font-extrabold hover:underline">
              Kembali
            </a>
          </div>
        </form>

      </div>
    </div>
  </div>

  {{-- Script Toggle Password --}}
  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const eyeOpen = document.getElementById('eyeOpen');
      const eyeClosed = document.getElementById('eyeClosed');

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