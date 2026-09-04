@extends('layouts.app')

@section('title', 'Form Pengisian Logsheet - ' . $machine->name)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Header info -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="text-xs text-slate-400 mb-1 flex items-center space-x-2">
                    <a href="{{ route('logsheet.index') }}" class="text-emerald-700 hover:underline">{{ $machine->kategori->name ?? '-' }}</a>
                    <span>&rarr;</span>
                    <a href="{{ route('logsheet.machines', [$machine->kategori_id, $machine->building_id]) }}" class="text-emerald-700 hover:underline">{{ $machine->bangunan->name ?? '-' }}</a>
                    <span>&bull;</span>
                    <span>Formulir Logsheet</span>
                </nav>
                <h1 class="text-2xl font-headline font-bold text-[#1E3A5F]">{{ $machine->name }}</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Kode: <span class="font-mono font-semibold text-[#1E3A5F]">{{ $machine->code }}</span> &bull; 
                    Lokasi: <span class="text-slate-700">{{ $machine->bangunan->location ?? 'Area Pabrik' }}</span> &bull;
                    Versi Form: <span class="text-slate-700">v{{ $template->version ?? 1 }}</span>
                </p>
            </div>
            <div class="text-right">
                <a href="{{ route('logsheet.machines', [$machine->kategori_id, $machine->building_id]) }}" 
                   class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-[#1E3A5F] bg-slate-100 hover:bg-slate-200 px-3.5 py-2 rounded-lg transition">
                    &larr; Ganti Mesin
                </a>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ route('logsheet.store', $machine->id) }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="form_template_id" value="{{ $template->id }}">

        <!-- Metadata Card (Tanggal, Shift, Teknisi) -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <h2 class="text-sm font-headline font-bold text-[#1E3A5F] uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                Informasi Pemeriksaan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Tanggal -->
                <div>
                    <label class="block text-xs font-semibold text-[#1E3A5F] mb-1.5">Tanggal Pemeriksaan *</label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                           class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 shadow-sm p-2.5">
                </div>

                <!-- Shift -->
                <div>
                    <label class="block text-xs font-semibold text-[#1E3A5F] mb-1.5">Shift Kerja *</label>
                    <select name="shift" required class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 shadow-sm p-2.5">
                        <option value="1" {{ old('shift') == '1' ? 'selected' : '' }}>Shift 1 (Pagi: 07.00 - 15.00)</option>
                        <option value="2" {{ old('shift') == '2' ? 'selected' : '' }}>Shift 2 (Sore: 15.00 - 23.00)</option>
                        <option value="3" {{ old('shift') == '3' ? 'selected' : '' }}>Shift 3 (Malam: 23.00 - 07.00)</option>
                    </select>
                </div>

                <!-- Petugas Teknisi -->
                <div>
                    <label class="block text-xs font-semibold text-[#1E3A5F] mb-1.5">Petugas / Teknisi</label>
                    <input type="text" readonly value="{{ $currentUser->name ?? 'Teknisi Demo' }}"
                           class="w-full text-sm rounded-lg border-slate-200 bg-slate-50 text-slate-500 shadow-sm cursor-not-allowed p-2.5">
                </div>
            </div>
        </div>

        <!-- Dynamic Parameters Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-base font-headline font-bold text-[#1E3A5F]">Parameter Pemeriksaan Mesin</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Isi nilai riil dan tentukan status kondisi dengan mengacu pada pedoman syarat.</p>
                </div>
                <div class="flex items-center space-x-2 text-xs">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#DCFCE7] text-[#166534] font-semibold border border-emerald-200">
                        &check; Baik = Normal
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#FEE2E2] text-[#991B1B] font-semibold border border-rose-200">
                        &excl; Perlu Perhatian = Abnormal
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5 w-12 text-center">No</th>
                            <th class="px-6 py-3.5">Parameter Pemeriksaan</th>
                            <th class="px-6 py-3.5">Syarat (Acuan/Pedoman)</th>
                            <th class="px-6 py-3.5 w-48">Nilai Pengukuran</th>
                            <th class="px-6 py-3.5 w-64">Status Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($template->parameters as $index => $param)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 text-center font-semibold text-slate-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-[#1E3A5F] block">{{ $param->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2.5 py-1 rounded-md bg-[#FEF3C7] border border-amber-200 text-[#92400E] text-xs font-semibold">
                                        {{ $param->requirement ?? 'Bebas / Kondisi Baik' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text" 
                                           name="params[{{ $param->id }}][value]" 
                                           value="{{ old('params.' . $param->id . '.value') }}"
                                           placeholder="Isi hasil periksa..."
                                           required
                                           class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 shadow-sm py-1.5 px-3">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <label class="flex items-center space-x-1.5 cursor-pointer text-xs font-semibold text-[#166534] bg-[#DCFCE7] hover:bg-emerald-100 px-3 py-1.5 rounded-lg border border-emerald-300 transition">
                                            <input type="radio" 
                                                   name="params[{{ $param->id }}][condition_status]" 
                                                   value="baik" 
                                                   {{ old('params.' . $param->id . '.condition_status', 'baik') === 'baik' ? 'checked' : '' }}
                                                   class="text-emerald-600 focus:ring-emerald-500">
                                            <span>Baik</span>
                                        </label>

                                        <label class="flex items-center space-x-1.5 cursor-pointer text-xs font-semibold text-[#991B1B] bg-[#FEE2E2] hover:bg-rose-100 px-3 py-1.5 rounded-lg border border-rose-300 transition">
                                            <input type="radio" 
                                                   name="params[{{ $param->id }}][condition_status]" 
                                                   value="perlu_perhatian"
                                                   {{ old('params.' . $param->id . '.condition_status') === 'perlu_perhatian' ? 'checked' : '' }}
                                                   class="text-rose-600 focus:ring-rose-500">
                                            <span>Perlu Perhatian</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada parameter untuk mesin ini. Silakan atur terlebih dahulu di menu 
                                    <a href="{{ route('form-builder.edit', $machine->id) }}" class="text-emerald-700 font-semibold underline">Form Builder</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons (Submit ke SPV / Simpan Draft) -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-xs text-slate-500">
                <span class="font-semibold text-[#1E3A5F]">Catatan:</span> Klik "Submit Logsheet" untuk langsung meneruskan data ke antrian approval Supervisor.
            </div>
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <button type="submit" name="action" value="draft" 
                        class="w-1/2 sm:w-auto px-5 py-2.5 rounded-lg text-sm font-semibold text-[#1E3A5F] bg-slate-100 hover:bg-slate-200 transition">
                    Simpan Draft
                </button>
                <button type="submit" name="action" value="submit" 
                        class="w-1/2 sm:w-auto px-6 py-2.5 rounded-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/40 transition">
                    🚀 Submit Logsheet
                </button>
            </div>
        </div>

    </form>

</div>
@endsection
