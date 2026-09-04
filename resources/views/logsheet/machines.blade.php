@extends('layouts.app')

@section('title', 'Pilih & Isi Logsheet Mesin - ' . $building->name)

@section('content')
<div class="max-w-7xl mx-auto" 
     x-data="{ 
         activeId: {{ request('machine_id', $selectedMachineId ?? ($machines->first()->id ?? 0)) }},
         search: '',
         mobileShowDetail: false
     }">

    <!-- Breadcrumb Nav -->
    <div class="mb-4 flex items-center justify-between text-xs text-slate-500">
        <div class="flex items-center space-x-2">
            <a href="{{ route('home') }}" class="hover:text-teal-600">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('logsheet.index') }}" class="hover:text-teal-600">{{ $category->name }}</a>
            <span>&rsaquo;</span>
            <a href="{{ route('logsheet.buildings', $category->id) }}" class="hover:text-teal-600">{{ $building->name }}</a>
            <span>&rsaquo;</span>
            <span class="font-bold text-slate-800">Unit Mesin</span>
        </div>
        <div class="hidden sm:flex items-center space-x-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Split-Sidebar Interaktif (WhatsApp Model)</span>
        </div>
    </div>

    <!-- WhatsApp Web Style Split Layout Window -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col md:flex-row h-[820px] max-h-[calc(100vh-8.5rem)]">

        <!-- ==================== LEFT SIDEBAR (DAFTAR MESIN) ==================== -->
        <div class="w-full md:w-80 lg:w-96 flex-shrink-0 border-r border-slate-200/80 bg-white flex flex-col h-full"
             :class="mobileShowDetail ? 'hidden md:flex' : 'flex'">

            <!-- Sidebar Top Header -->
            <div class="p-4 bg-slate-50/80 border-b border-slate-200/80">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-base shadow-sm shadow-teal-200">
                            🏢
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 leading-tight">{{ $building->name }}</h2>
                            <p class="text-[11px] text-slate-400">{{ $category->name }} &bull; {{ $building->location ?? 'Area Pabrik' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('logsheet.buildings', $category->id) }}" 
                       title="Ganti Gedung"
                       class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center text-xs transition">
                        &larr;
                    </a>
                </div>

                <!-- Search Input (WhatsApp search bar style) -->
                <div class="relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" 
                           x-model="search" 
                           placeholder="Cari nama atau kode mesin..." 
                           class="w-full text-xs rounded-xl bg-white border border-slate-200 pl-9 pr-3 py-2 text-slate-700 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-sm placeholder:text-slate-400">
                </div>
            </div>

            <!-- Scrollable Machine List Items -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100">
                @forelse($machines as $m)
                    @php
                        $template = $m->latestTemplate;
                        $paramCount = $template ? $template->parameters->count() : 0;
                        $lastLogsheet = $m->logsheets->first();
                    @endphp
                    <div @click="activeId = {{ $m->id }}; mobileShowDetail = true"
                         x-show="!search || '{{ strtolower($m->name . ' ' . $m->code) }}'.includes(search.toLowerCase())"
                         :class="activeId === {{ $m->id }} ? 'bg-teal-50/70 border-l-4 border-teal-600' : 'hover:bg-slate-50/90 border-l-4 border-transparent'"
                         class="p-3.5 cursor-pointer transition flex items-start space-x-3 select-none">
                        
                        <!-- Avatar / Machine Icon with active dot -->
                        <div class="relative flex-shrink-0">
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-lg font-bold shadow-xs transition"
                                 :class="activeId === {{ $m->id }} ? 'bg-teal-600 text-white shadow-teal-200' : 'bg-slate-100 text-slate-600'">
                                @if($category->code === 'HVAC')
                                    💨
                                @elseif($category->code === 'ME')
                                    ⚡
                                @elseif($category->code === 'CU')
                                    ❄️
                                @else
                                    ⚙️
                                @endif
                            </div>
                            <span class="w-3 h-3 rounded-full {{ $m->status_aktif ? 'bg-emerald-500 ring-2 ring-white' : 'bg-slate-400' }} absolute -bottom-0.5 -right-0.5"></span>
                        </div>

                        <!-- Info Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <h3 class="text-xs font-bold text-slate-900 truncate"
                                    :class="activeId === {{ $m->id }} ? 'text-teal-900' : 'text-slate-800'">
                                    {{ $m->name }}
                                </h3>
                                @if($lastLogsheet)
                                    <span class="text-[10px] text-slate-400 font-medium">
                                        {{ $lastLogsheet->date ? $lastLogsheet->date->format('d/m') : '' }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span class="font-mono text-slate-400">{{ $m->code }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-md font-medium"
                                      :class="activeId === {{ $m->id }} ? 'bg-teal-100 text-teal-800' : 'bg-slate-100 text-slate-600'">
                                    {{ $paramCount }} Param
                                </span>
                            </div>

                            @if($lastLogsheet)
                                <p class="text-[10px] text-slate-400 mt-1 truncate">
                                    Terakhir: Shift {{ $lastLogsheet->shift }} ({{ $lastLogsheet->teknisi->name ?? '-' }})
                                </p>
                            @else
                                <p class="text-[10px] text-teal-600 font-medium mt-1">
                                    Belum ada logsheet hari ini &bull; Klik untuk isi
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        Tidak ada unit mesin di gedung ini.
                    </div>
                @endforelse
            </div>

            <!-- Sidebar Bottom Info -->
            <div class="p-3 bg-slate-50 border-t border-slate-200/80 text-[11px] text-slate-500 flex justify-between items-center">
                <span>{{ $machines->count() }} Mesin di Gedung ini</span>
                <span class="text-teal-600 font-medium">{{ $category->code }}</span>
            </div>
        </div>


        <!-- ==================== RIGHT PANEL (FORM PENGISIAN & EMBEDDED FORM BUILDER) ==================== -->
        <div class="flex-1 flex flex-col bg-white overflow-hidden h-full"
             :class="mobileShowDetail ? 'flex' : 'hidden md:flex'">

            @forelse($machines as $machine)
                @php
                    $template = $machine->latestTemplate;
                @endphp
                <div x-show="activeId === {{ $machine->id }}" 
                     x-data="{ activeTab: '{{ (request('machine_id') == $machine->id) ? request('tab', 'form') : 'form' }}' }"
                     class="flex flex-col h-full overflow-hidden" 
                     style="display: none;">

                    <!-- Right Panel Top Header -->
                    <div class="p-4 bg-slate-50/80 border-b border-slate-200/80 flex items-center justify-between flex-shrink-0">
                        <div class="flex items-center space-x-3">
                            <!-- Mobile Back Button -->
                            <button @click="mobileShowDetail = false" class="md:hidden text-slate-600 hover:text-slate-900 pr-2">
                                &larr;
                            </button>

                            <div class="w-10 h-10 rounded-2xl bg-teal-600 text-white flex items-center justify-center text-lg font-bold shadow-sm shadow-teal-200">
                                @if($category->code === 'HVAC') 💨 @elseif($category->code === 'ME') ⚡ @elseif($category->code === 'CU') ❄️ @else ⚙️ @endif
                            </div>

                            <div>
                                <div class="flex items-center space-x-2">
                                    <h2 class="text-sm font-bold text-slate-900">{{ $machine->name }}</h2>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        ● Aktif
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500">
                                    Kode: <span class="font-mono font-semibold">{{ $machine->code }}</span> &bull;
                                    Lokasi: {{ $building->name }} ({{ $building->location ?? 'Pabrik' }}) &bull;
                                    Template: <strong>v{{ $template->version ?? 1 }}</strong>
                                </p>
                            </div>
                        </div>

                        <!-- Embedded Tab Switcher: Form Logsheet vs Form Builder -->
                        <div class="flex items-center bg-slate-200/70 p-1 rounded-xl text-xs font-semibold">
                            <button type="button" 
                                    @click="activeTab = 'form'" 
                                    :class="activeTab === 'form' ? 'bg-white text-teal-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                                    class="px-3 py-1.5 rounded-lg transition flex items-center space-x-1.5">
                                <span>📝 Isi Logsheet</span>
                            </button>
                            <button type="button" 
                                    @click="activeTab = 'builder'" 
                                    :class="activeTab === 'builder' ? 'bg-white text-teal-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                                    class="px-3 py-1.5 rounded-lg transition flex items-center space-x-1.5">
                                <span>⚙️ Form Builder ({{ $template->parameters->count() }})</span>
                            </button>
                        </div>
                    </div>


                    <!-- ==================== TAB 1: FORM PENGISIAN LOGSHEET ==================== -->
                    <div x-show="activeTab === 'form'" class="flex-1 flex flex-col overflow-hidden">
                        <form action="{{ route('logsheet.store', $machine->id) }}" method="POST" class="flex-1 flex flex-col overflow-hidden">
                            @csrf
                            <input type="hidden" name="form_template_id" value="{{ $template->id }}">

                            <div class="flex-1 overflow-y-auto p-5 space-y-5">

                                <!-- Info Bar: Tanggal, Shift, Petugas -->
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tanggal Pemeriksaan *</label>
                                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                                   class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-500 focus:ring focus:ring-teal-200 shadow-sm py-1.5 px-3">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Shift Kerja *</label>
                                            <select name="shift" required class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-500 focus:ring focus:ring-teal-200 shadow-sm py-1.5 px-3">
                                                <option value="1" {{ old('shift') == '1' ? 'selected' : '' }}>Shift 1 (Pagi: 07.00 - 15.00)</option>
                                                <option value="2" {{ old('shift') == '2' ? 'selected' : '' }}>Shift 2 (Sore: 15.00 - 23.00)</option>
                                                <option value="3" {{ old('shift') == '3' ? 'selected' : '' }}>Shift 3 (Malam: 23.00 - 07.00)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Teknisi Pelaksana</label>
                                            <input type="text" readonly value="{{ $currentUser->name ?? 'Teknisi Lapangan' }}"
                                                   class="w-full text-xs rounded-xl border-slate-200 bg-white/70 text-slate-500 shadow-sm py-1.5 px-3 cursor-not-allowed">
                                        </div>
                                    </div>
                                </div>

                                <!-- Parameter Table -->
                                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
                                    <div class="p-3.5 bg-slate-50/90 border-b border-slate-200 flex justify-between items-center text-xs">
                                        <div>
                                            <span class="font-bold text-slate-800">Parameter Pemeriksaan</span>
                                            <span class="text-slate-400 text-[11px] ml-1">({{ $template->parameters->count() }} item)</span>
                                        </div>
                                        <button type="button" 
                                                @click="activeTab = 'builder'"
                                                class="text-[11px] font-semibold text-teal-600 hover:text-teal-800 hover:underline flex items-center space-x-1">
                                            <span>+ Kelola / Tambah Parameter</span>
                                        </button>
                                    </div>

                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-50/50 text-slate-500 uppercase font-semibold border-b border-slate-200 text-[10px]">
                                            <tr>
                                                <th class="px-4 py-2.5 w-10 text-center">No</th>
                                                <th class="px-4 py-2.5">Parameter</th>
                                                <th class="px-4 py-2.5">Syarat (Acuan)</th>
                                                <th class="px-4 py-2.5 w-44">Nilai Isian</th>
                                                <th class="px-4 py-2.5 w-48 text-center">Kondisi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @forelse($template->parameters as $idx => $param)
                                                <tr class="hover:bg-slate-50/60 transition">
                                                    <td class="px-4 py-3 text-center text-slate-400 font-bold">
                                                        {{ $idx + 1 }}
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="font-bold text-slate-900 block">{{ $param->name }}</span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="inline-block px-2 py-0.5 rounded bg-amber-50 text-amber-900 border border-amber-200 text-[11px] font-medium">
                                                            {{ $param->requirement ?? 'Baik' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <input type="text" 
                                                               name="params[{{ $param->id }}][value]" 
                                                               value="{{ old('params.' . $param->id . '.value') }}"
                                                               placeholder="Hasil periksa..."
                                                               required
                                                               class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-500 focus:ring focus:ring-teal-200 py-1.5 px-3">
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex items-center justify-center space-x-2">
                                                            <label class="flex items-center space-x-1 cursor-pointer text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-lg border border-emerald-200 transition">
                                                                <input type="radio" 
                                                                       name="params[{{ $param->id }}][condition_status]" 
                                                                       value="baik" 
                                                                       {{ old('params.' . $param->id . '.condition_status', 'baik') === 'baik' ? 'checked' : '' }}
                                                                       class="text-emerald-600 focus:ring-emerald-500 w-3.5 h-3.5">
                                                                <span>Baik</span>
                                                            </label>

                                                            <label class="flex items-center space-x-1 cursor-pointer text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded-lg border border-rose-200 transition">
                                                                <input type="radio" 
                                                                       name="params[{{ $param->id }}][condition_status]" 
                                                                       value="perlu_perhatian"
                                                                       {{ old('params.' . $param->id . '.condition_status') === 'perlu_perhatian' ? 'checked' : '' }}
                                                                       class="text-rose-600 focus:ring-rose-500 w-3.5 h-3.5">
                                                                <span>Abnormal</span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                                        Belum ada parameter form untuk mesin ini. 
                                                        <button type="button" @click="activeTab = 'builder'" class="text-teal-600 underline font-semibold ml-1">
                                                            Klik di sini untuk menambah parameter
                                                        </button>.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <!-- Action Footer -->
                            <div class="p-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between flex-shrink-0">
                                <span class="text-[11px] text-slate-500">
                                    Satu submission untuk seluruh parameter mesin ini
                                </span>

                                <div class="flex items-center space-x-2">
                                    <button type="submit" name="action" value="draft" 
                                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 transition shadow-xs">
                                        Simpan Draft
                                    </button>
                                    <button type="submit" name="action" value="submit" 
                                            class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-teal-600 hover:bg-teal-700 shadow-md shadow-teal-200 transition">
                                        🚀 Submit ke SPV
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>


                    <!-- ==================== TAB 2: EMBEDDED FORM BUILDER ==================== -->
                    <div x-show="activeTab === 'builder'" class="flex-1 overflow-y-auto p-5 space-y-5" style="display: none;">

                        <!-- Card Tambah Parameter Baru Langsung di Mesin ini -->
                        <div class="bg-teal-50/50 p-4 rounded-2xl border border-teal-200/70">
                            <h3 class="text-xs font-bold text-teal-900 uppercase tracking-wider mb-2">
                                + Tambah Parameter Baru untuk {{ $machine->name }}
                            </h3>
                            <p class="text-[11px] text-slate-500 mb-3">
                                Parameter yang ditambahkan akan otomatis muncul di form pengisian logsheet mesin ini.
                            </p>

                            <form action="{{ route('form-builder.parameter.store', $machine->id) }}?machine_id={{ $machine->id }}&tab=builder" method="POST">
                                @csrf
                                <input type="hidden" name="template_id" value="{{ $template->id }}">

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                    <div class="sm:col-span-6">
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama Parameter *</label>
                                        <input type="text" name="name" required placeholder="Contoh: Suhu Bearing, Tekanan Oli, Tegangan Accu"
                                               class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-500 focus:ring focus:ring-teal-200 py-1.5 px-3">
                                    </div>
                                    <div class="sm:col-span-4">
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Syarat / Acuan (Teks Bebas)</label>
                                        <input type="text" name="requirement" placeholder="Contoh: < 35 °C, 3 - 5 Bar, Baik"
                                               class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-500 focus:ring focus:ring-teal-200 py-1.5 px-3">
                                    </div>
                                    <div class="sm:col-span-2 flex items-end">
                                        <button type="submit" 
                                                class="w-full py-2 px-3 rounded-xl text-xs font-bold text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition">
                                            + Tambah
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Daftar Parameter Aktif -->
                        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
                            <div class="p-3.5 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs">
                                <div>
                                    <span class="font-bold text-slate-800">Daftar Parameter Terdaftar</span>
                                    <span class="text-slate-400 text-[11px] ml-1">({{ $template->parameters->count() }} parameter)</span>
                                </div>
                                <button type="button" 
                                        @click="activeTab = 'form'" 
                                        class="px-3 py-1 rounded-lg text-xs font-bold text-teal-700 bg-teal-100/60 hover:bg-teal-200 transition">
                                    &larr; Selesai & Kembali ke Form
                                </button>
                            </div>

                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50/50 text-slate-500 uppercase font-semibold border-b border-slate-200 text-[10px]">
                                    <tr>
                                        <th class="px-4 py-2.5 w-12 text-center">Urutan</th>
                                        <th class="px-4 py-2.5">Nama Parameter</th>
                                        <th class="px-4 py-2.5">Syarat (Acuan Teks Bebas)</th>
                                        <th class="px-4 py-2.5 text-right w-36">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($template->parameters as $idx => $param)
                                        <tr class="hover:bg-slate-50/60 transition" x-data="{ editing: false }">
                                            <td class="px-4 py-3 text-center text-slate-400 font-bold">
                                                {{ $idx + 1 }}
                                            </td>

                                            <!-- Normal Mode -->
                                            <td class="px-4 py-3 font-bold text-slate-900" x-show="!editing">
                                                {{ $param->name }}
                                            </td>
                                            <td class="px-4 py-3" x-show="!editing">
                                                <span class="bg-amber-50 text-amber-900 border border-amber-200 text-[11px] px-2 py-0.5 rounded-md font-medium">
                                                    {{ $param->requirement ?? 'Bebas / Baik' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right space-x-2" x-show="!editing">
                                                <button type="button" @click="editing = true" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                                                    Edit
                                                </button>
                                                <form action="{{ route('form-builder.parameter.destroy', $param->id) }}?machine_id={{ $machine->id }}&tab=builder" method="POST" class="inline" onsubmit="return confirm('Hapus parameter ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>

                                            <!-- Inline Edit Mode -->
                                            <td colspan="3" class="px-4 py-2.5 bg-teal-50/40" x-show="editing" style="display: none;">
                                                <form action="{{ route('form-builder.parameter.update', $param->id) }}?machine_id={{ $machine->id }}&tab=builder" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="name" value="{{ $param->name }}" required
                                                           class="text-xs rounded-lg border-slate-300 focus:ring-teal-500 py-1 px-2 w-1/2">
                                                    <input type="text" name="requirement" value="{{ $param->requirement }}" placeholder="Syarat..."
                                                           class="text-xs rounded-lg border-slate-300 focus:ring-teal-500 py-1 px-2 w-1/3">
                                                    <button type="submit" class="text-xs px-2.5 py-1 bg-teal-600 text-white rounded font-bold">
                                                        Simpan
                                                    </button>
                                                    <button type="button" @click="editing = false" class="text-xs px-2 py-1 text-slate-500 hover:text-slate-700">
                                                        Batal
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">
                                                Belum ada parameter terdaftar. Tambahkan parameter melalui form di atas.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            @empty
                <!-- Empty State -->
                <div class="flex-1 flex flex-col items-center justify-center p-12 text-center text-slate-400">
                    <div class="w-16 h-16 rounded-3xl bg-slate-100 flex items-center justify-center text-3xl mb-4">
                        🏭
                    </div>
                    <h3 class="text-base font-bold text-slate-700">Belum Ada Mesin</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm">
                        Tidak ada mesin aktif yang terdaftar pada kategori dan gedung ini.
                    </p>
                </div>
            @endforelse

            <!-- Default Empty placeholder when no machine selected -->
            <div x-show="!activeId && {{ $machines->count() }} > 0" 
                 class="flex-1 flex flex-col items-center justify-center p-12 text-center text-slate-400">
                <div class="w-16 h-16 rounded-3xl bg-teal-50 text-teal-600 flex items-center justify-center text-3xl mb-4">
                    💬
                </div>
                <h3 class="text-base font-bold text-slate-700">Pilih Unit Mesin</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm">
                    Pilih salah satu unit mesin di panel sebelah kiri untuk langsung membuka formulir logsheet dan form builder.
                </p>
            </div>

        </div>

    </div>

</div>
@endsection
