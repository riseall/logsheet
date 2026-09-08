<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Logsheet Monitoring Mesin</title>

    <!-- Google Fonts: Nunito, Open Sans, Roboto Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Roboto+Mono:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Open Sans"', 'system-ui', 'sans-serif'],
                        headline: ['Nunito', 'sans-serif'],
                        mono: ['"Roboto Mono"', 'monospace'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#059669',
                            hover: '#047857',
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            600: '#059669',
                            700: '#047857',
                        },
                        navy: {
                            DEFAULT: '#1E3A5F',
                            dark: '#13243B',
                            light: '#2A4D7A',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body
    class="h-full flex flex-col justify-between text-slate-800 antialiased font-sans bg-gradient-to-br from-slate-100 via-slate-50 to-emerald-50/40">

    <!-- Main Container -->
    <main class="flex-grow flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">

            <!-- Flash Error / Success Messages -->
            @if (session('error'))
                <div
                    class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start space-x-3 shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <span class="font-bold block font-headline">Gagal Masuk</span>
                        <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div
                    class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start space-x-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div>
                        <span class="font-bold block font-headline">Pemberitahuan</span>
                        <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Login Card -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="px-6 sm:px-10 pt-8 pb-6 border-b border-slate-100">
                    <div class="text-center">
                        <div
                            class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 mb-3 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="font-headline font-black text-2xl text-[#1E3A5F] tracking-tight">Masuk ke Akun</h2>
                        <p class="text-xs text-slate-500 mt-1">Masukkan NIK dan kata sandi Anda untuk melanjutkan.</p>
                    </div>
                </div>

                <!-- Form Login -->
                <form action="{{ route('login.post') }}" method="POST" class="px-6 sm:px-10 pt-6 pb-8 space-y-5">
                    @csrf

                    <!-- NIK Input -->
                    <div>
                        <label for="nik"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 font-headline">NIK</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required
                                autofocus
                                class="block w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border @error('nik') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition placeholder-slate-400"
                                placeholder="Masukkan NIK Anda">
                        </div>
                        @error('nik')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div x-data="{ showPass: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password"
                                class="block text-xs font-bold text-slate-700 uppercase tracking-wider font-headline">Kata
                                Sandi</label>
                        </div>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input :type="showPass ? 'text' : 'password'" id="password" name="password" required
                                class="block w-full pl-10 pr-10 py-2.5 text-sm bg-slate-50 border @error('password') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition placeholder-slate-400"
                                placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember & Action -->
                    {{-- <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer select-none">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 transition"
                                checked>
                            <span class="ml-2 text-xs text-slate-600 font-medium">Ingat saya di sesi ini</span>
                        </label>
                    </div> --}}

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-headline font-bold text-sm shadow-lg shadow-emerald-600/25 hover:shadow-emerald-600/35 transition duration-150 flex items-center justify-center space-x-2 group">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </main>

</body>

</html>
