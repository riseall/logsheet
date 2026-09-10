<header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo & Nav Links -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xl shadow-md shadow-emerald-600/20 group-hover:bg-emerald-700 transition">
                        ⚙️
                    </div>
                    <div>
                        <span class="font-headline font-extrabold text-lg text-[#1E3A5F] tracking-tight block">LOGSHEET</span>
                        <span class="text-xs text-slate-400 font-medium block -mt-1">Monitoring Mesin</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1">
                    <!-- Home -->
                    <a href="{{ route('home') }}"
                        class="px-3.5 py-2 rounded-xl text-sm font-semibold transition flex items-center space-x-2 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span>Home</span>
                    </a>

                    <!-- LogSheet Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                            class="px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center border {{ request()->routeIs('logsheet.*') ? 'bg-emerald-50 text-emerald-700 border-emerald-300 shadow-sm' : 'text-slate-600 border-transparent hover:bg-slate-100 hover:text-[#1E3A5F]' }}">
                            <svg class="w-4 h-4 mr-2 {{ request()->routeIs('logsheet.*') ? 'text-emerald-600' : 'text-slate-500' }}"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                            </svg>
                            <span>LogSheet</span>
                            <svg class="w-3.5 h-3.5 ml-1.5 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Panel -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 p-3 z-50"
                            style="display: none;">

                            <div class="px-3 py-1.5 text-[11px] font-bold text-emerald-700 tracking-wider uppercase font-headline">
                                MONITORING LOGSHEET
                            </div>

                            <div class="space-y-0.5 mt-1">
                                @foreach ($navCategories ?? [] as $cat)
                                    <a href="{{ route('logsheet.buildings', $cat->id) }}"
                                        class="group flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/60 transition">
                                        <x-category-icon :code="$cat->code" class="w-4 h-4 text-slate-500 group-hover:text-emerald-600 transition" />
                                        <span>{{ $cat->code }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>



                    <!-- Approval (Supervisor, Manager, Admin) -->
                    @if (!($currentUser && $currentUser->isTeknisi()))
                        <a href="{{ route('approval.index') }}"
                            class="px-3.5 py-2 rounded-xl text-sm font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('approval.*') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-50' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('approval.*') ? 'text-emerald-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Approval</span>
                            @php
                                $pendingCount = 0;
                                if ($currentUser) {
                                    if ($currentUser->isManager()) {
                                        $pendingCount = \App\Models\LogsheetHeader::where('status', 'menunggu_manager')->count();
                                    } elseif ($currentUser->isSupervisor()) {
                                        $pendingCount = \App\Models\LogsheetHeader::where('status', 'menunggu_spv')->count();
                                    } else {
                                        $pendingCount = \App\Models\LogsheetHeader::whereIn('status', ['menunggu_spv', 'menunggu_manager'])->count();
                                    }
                                }
                            @endphp
                            @if ($pendingCount > 0)
                                <span class="bg-orange-100 text-[#EA580C] text-xs font-bold px-2 py-0.5 rounded-full border border-orange-200">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    @endif

                    <!-- Laporan -->
                    <a href="{{ route('laporan.index') }}"
                        class="px-3.5 py-2 rounded-xl text-sm font-semibold transition flex items-center space-x-2 {{ request()->routeIs('laporan.*') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('laporan.*') ? 'text-emerald-600' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                        </svg>
                        <span>Laporan</span>
                    </a>
                </nav>
            </div>

            <!-- Right Side: PMMT App Switcher, Demo Switcher, User Profile & Logout -->
            <div class="flex items-center space-x-3">
                @php
                    $pmmtUrl = session('return_app_url') ?: config('services.pmmt.url', 'http://pmmt-v2.test');
                @endphp
                <a href="{{ $pmmtUrl }}"
                    class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/80 transition shadow-sm group"
                    title="Aplikasi Manajemen Pemeliharaan Mesin (PMMT-v2)">
                    <svg class="w-3.5 h-3.5 text-emerald-600 transform group-hover:-translate-x-0.5 transition duration-150"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>{{ 'PMMT-v2' }}</span>
                </a>

                @if (isset($currentUser))
                    <div class="flex items-center space-x-2 border-l border-slate-200 pl-3">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs">
                            {{ strtoupper(substr($currentUser->name, 0, 2)) }}
                        </span>
                        <div class="hidden lg:block text-left">
                            <span class="text-xs font-semibold text-[#1E3A5F] block leading-tight">{{ $currentUser->name }}</span>
                            <span class="text-[10px] text-emerald-600 font-semibold tracking-wider">{{ $currentUser->role }}</span>
                        </div>

                        <!-- Logout Button -->
                        <form action="{{ route('logout') }}" method="POST" class="inline ml-1">
                            @csrf
                            <button type="submit" title="Keluar Akun"
                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>
