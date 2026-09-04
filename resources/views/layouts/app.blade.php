<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Logsheet Monitoring Mesin Produksi')</title>
    
    <!-- Google Fonts: Nunito (Headings), Open Sans (Body), Roboto Mono (Technical Code) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Roboto+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- TailwindCSS CDN & Compiled Mix -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Open Sans"', 'system-ui', 'sans-serif'],
                        headline: ['Nunito', 'sans-serif'],
                        body: ['"Open Sans"', 'sans-serif'],
                        mono: ['"Roboto Mono"', 'monospace'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#059669',
                            hover: '#047857',
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        secondary: {
                            DEFAULT: '#EA580C',
                            hover: '#C2410C',
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                        },
                        tertiary: '#1E3A5F',
                        cause: {
                            green: '#059669',
                            'green-dark': '#047857',
                            orange: '#EA580C',
                            'orange-dark': '#C2410C',
                            navy: '#1E3A5F',
                            'navy-light': '#2A4D7A',
                            surface: '#FFFFFF',
                        },
                        // Ponytail: Map teal classes to CauseConnect primary emerald
                        teal: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669', // CauseConnect Primary
                            700: '#047857', // CauseConnect Primary Hover
                            800: '#065f46',
                            900: '#064e3b',
                        },
                    },
                    borderRadius: {
                        'cause-sm': '4px',
                        'cause-md': '8px',
                        'cause-lg': '12px',
                        'cause-full': '9999px',
                    },
                    boxShadow: {
                        'cause-sm': '0 1px 3px rgba(0, 0, 0, 0.06)',
                        'cause-md': '0 4px 8px rgba(0, 0, 0, 0.08)',
                        'cause-lg': '0 8px 20px rgba(0, 0, 0, 0.10)',
                        'green': '0 4px 12px rgba(5, 150, 105, 0.20)',
                        'orange': '0 4px 12px rgba(234, 88, 12, 0.20)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* CauseConnect Foundations */
        body {
            font-family: 'Open Sans', sans-serif;
            color: #1E3A5F;
            background-color: #F8FAFC;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .font-headline {
            font-family: 'Nunito', sans-serif;
            color: #1E3A5F;
        }

        code, kbd, samp, pre, .font-mono {
            font-family: 'Roboto Mono', monospace;
        }

        /* CauseConnect Components Helpers */
        .btn-cause-primary {
            background-color: #059669;
            color: #FFFFFF;
            font-family: 'Open Sans', sans-serif;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-cause-primary:hover {
            background-color: #047857;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.20);
        }

        .btn-cause-secondary {
            background-color: #EA580C;
            color: #FFFFFF;
            font-family: 'Open Sans', sans-serif;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-cause-secondary:hover {
            background-color: #C2410C;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.20);
        }

        .btn-cause-ghost {
            background-color: transparent;
            color: #059669;
            border: 2px solid #059669;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-cause-ghost:hover {
            background-color: rgba(5, 150, 105, 0.08);
        }
    </style>
</head>
<body class="bg-slate-50 text-[#1E3A5F] min-h-screen flex flex-col antialiased">

    <!-- Top Horizontal Navbar (CauseConnect Styled) -->
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
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            <span>Home</span>
                        </a>

                        <!-- LogSheet Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" 
                                    class="px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center border {{ request()->routeIs('logsheet.*') ? 'bg-emerald-50 text-emerald-700 border-emerald-300 shadow-sm' : 'text-slate-600 border-transparent hover:bg-slate-100 hover:text-[#1E3A5F]' }}">
                                <svg class="w-4 h-4 mr-2 {{ request()->routeIs('logsheet.*') ? 'text-emerald-600' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                </svg>
                                <span>LogSheet</span>
                                <svg class="w-3.5 h-3.5 ml-1.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <!-- Dropdown Panel -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
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
                                    @foreach($navCategories ?? [] as $cat)
                                        <a href="{{ route('logsheet.buildings', $cat->id) }}" 
                                           class="group flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/60 transition">
                                            @if($cat->code === 'HVAC')
                                                <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"/>
                                                </svg>
                                            @elseif($cat->code === 'ME')
                                                <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-600 transition" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M13 2L3 14h7v8l10-12h-7z"/>
                                                </svg>
                                            @elseif($cat->code === 'CU')
                                                <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m0-18l-3 3m3-3l3 3m-3 15l-3-3m3 3l3-3M4 12h16m-16 0l3-3m-3 3l3 3m13-3l3-3m-3 3l3 3"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                </svg>
                                            @endif
                                            <span>{{ $cat->code }}</span>
                                        </a>
                                    @endforeach
                                </div>

                                <div class="mt-2 pt-2 border-t border-slate-100">
                                    <a href="{{ route('logsheet.index') }}" 
                                       class="flex items-center justify-between px-3 py-1.5 rounded-lg text-[11px] font-semibold text-slate-400 hover:text-emerald-700 hover:bg-emerald-50/60 transition">
                                        <span>Semua Kategori</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Approval -->
                        <a href="{{ route('approval.index') }}" 
                           class="px-3.5 py-2 rounded-xl text-sm font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('approval.*') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-50' }}">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Approval</span>
                            @php
                                $pendingCount = \App\Models\LogsheetHeader::whereIn('status', ['menunggu_spv', 'menunggu_manager'])->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="bg-orange-100 text-[#EA580C] text-xs font-bold px-2 py-0.5 rounded-full border border-orange-200">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Laporan -->
                        <a href="{{ route('laporan.index') }}" 
                           class="px-3.5 py-2 rounded-xl text-sm font-semibold transition flex items-center space-x-2 {{ request()->routeIs('laporan.*') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-50' }}">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                            </svg>
                            <span>Laporan</span>
                        </a>
                    </nav>
                </div>

                <!-- Right Side: Demo User & Role Switcher -->
                <div class="flex items-center space-x-3">
                    <div class="hidden sm:flex items-center bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                        <span class="text-xs text-slate-500 font-medium mr-2">Simulasi Role:</span>
                        <form action="{{ route('switch-role') }}" method="POST" class="inline">
                            @csrf
                            <select name="user_id" onchange="this.form.submit()" class="text-xs bg-white font-semibold border-none rounded py-1 px-2 text-[#1E3A5F] focus:ring-2 focus:ring-emerald-500 shadow-sm cursor-pointer">
                                @foreach($allDemoUsers ?? [] as $u)
                                    <option value="{{ $u->id }}" {{ ($currentUser->id ?? null) == $u->id ? 'selected' : '' }}>
                                        {{ ucfirst($u->role) }} ({{ $u->name }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    @if(isset($currentUser))
                        <div class="flex items-center space-x-2 border-l border-slate-200 pl-3">
                            <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($currentUser->name, 0, 2)) }}
                            </span>
                            <div class="hidden lg:block text-left">
                                <span class="text-xs font-semibold text-[#1E3A5F] block leading-tight">{{ $currentUser->name }}</span>
                                <span class="text-[10px] text-emerald-600 font-semibold uppercase tracking-wider">{{ $currentUser->role }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-sm mb-4" x-data="{ show: true }" x-show="show">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-sm mb-4" x-data="{ show: true }" x-show="show">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-xl text-sm shadow-sm mb-4">
                <div class="font-bold mb-1 font-headline">Terdapat kesalahan pengisian:</div>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
    </main>

    <!-- Simple Footer (CauseConnect Design) -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 space-y-2 sm:space-y-0">
            <div>
                &copy; {{ date('Y') }} Sistem Logsheet Monitoring Mesin Produksi. Sesuai PRD v1.3.
            </div>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    CauseConnect Design &middot; Ponytail Mode
                </span>
            </div>
        </div>
    </footer>

</body>
</html>
