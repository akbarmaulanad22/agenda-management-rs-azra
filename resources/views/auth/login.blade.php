<x-guest-layout>
    {{-- Left Column: Branding --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-[#007774] via-[#00635f] to-[#81bd41]">
        {{-- Abstract decorative shapes --}}
        <div class="absolute inset-0">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white/5 blur-sm"></div>
            <div class="absolute top-1/3 -right-20 w-80 h-80 rounded-full bg-white/5 blur-sm"></div>
            <div class="absolute -bottom-32 left-1/4 w-[500px] h-[500px] rounded-full bg-[#81bd41]/15 blur-sm"></div>
            <div class="absolute top-16 right-16 w-48 h-48 rounded-full border border-white/10"></div>
            <div class="absolute bottom-24 left-16 w-32 h-32 rounded-full border border-white/10"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full border border-white/5"></div>
        </div>

        {{-- Content --}}
        <div class="relative z-10 flex items-center justify-center w-full px-12 xl:px-20">
            <div>
                {{-- Glassmorphism card --}}
                <div class="backdrop-blur-xl bg-white/10 rounded-3xl p-10 border border-white/20 shadow-2xl max-w-lg">
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/15 backdrop-blur-sm text-white/90 text-xs font-semibold tracking-wider uppercase">
                            D-ASSA Platform
                        </span>
                    </div>

                    <h1 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight mb-6">
                        Sistem
                        <span class="block mt-1">Agenda</span>
                        <span class="block mt-1 text-[#81bd41]">Digital</span>
                    </h1>

                    <p class="text-white/75 text-base xl:text-lg leading-relaxed mb-8">
                        Platform modern untuk mengelola master data Agenda, dan absensi digital mandiri.
                    </p>

                </div>

            </div>
        </div>
    </div>

    {{-- Right Column: Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white px-6 py-12 sm:px-12">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-gradient-to-r from-[#007774] to-[#81bd41]">
                    <span class="text-white font-extrabold text-xl">D-ASSA</span>
                </div>
            </div>

            {{-- Header --}}
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang,</h2>
                <p class="mt-2 text-base text-gray-500">Silakan masuk ke akun Anda.</p>
            </div>

            {{-- Session Status --}}
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nama@email.com"
                        class="block w-full px-4 py-3.5 rounded-2xl border border-gray-200 bg-gray-50/50 text-gray-900 text-sm placeholder-gray-400 transition duration-200 focus:bg-white focus:border-[#007774] focus:ring-2 focus:ring-[#007774]/20 focus:outline-none"
                    >
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Kata Sandi</label>

                        {{-- Forgot password link --}}

                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan kata sandi"
                        class="block w-full px-4 py-3.5 rounded-2xl border border-gray-200 bg-gray-50/50 text-gray-900 text-sm placeholder-gray-400 transition duration-200 focus:bg-white focus:border-[#007774] focus:ring-2 focus:ring-[#007774]/20 focus:outline-none"
                    >
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Remember Me --}}

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full py-3.5 px-6 rounded-2xl bg-[#007774] text-white text-sm font-bold tracking-wide shadow-lg shadow-[#007774]/25 hover:bg-[#005c5a] hover:shadow-xl hover:shadow-[#007774]/30 active:scale-[0.98] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#007774]/50 focus:ring-offset-2"
                >
                    Masuk
                </button>
            </form>

            {{-- Register link --}}

            {{-- Footer --}}
            <p class="mt-12 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} D-ASSA. Digital Agenda & Self-Service Attendance.
            </p>
        </div>
    </div>
</x-guest-layout>
