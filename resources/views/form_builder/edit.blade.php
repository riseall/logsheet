@extends('layouts.app')

@section('title', 'Form Builder - ' . $machine->name)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="text-xs text-slate-400 mb-1 flex items-center space-x-2">
                    <a href="{{ route('form-builder.index') }}" class="text-emerald-700 hover:underline">&larr; Kembali ke Daftar Mesin</a>
                    <span>&bull;</span>
                    <span>Form Builder v{{ $template->version }}</span>
                </nav>
                <h1 class="text-2xl font-headline font-bold text-[#1E3A5F]">{{ $machine->name }}</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Kategori: <strong class="text-[#1E3A5F]">{{ $machine->kategori->name ?? '-' }}</strong> &bull;
                    Lokasi: <strong class="text-[#1E3A5F]">{{ $machine->bangunan->name ?? '-' }}</strong> &bull;
                    Kode: <span class="font-mono text-[#1E3A5F]">{{ $machine->code }}</span>
                </p>
            </div>
            <div>
                <a href="{{ route('logsheet.create', $machine->id) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold text-[#1E3A5F] bg-slate-100 hover:bg-slate-200 transition">
                    👁️ Preview Form Teknisi
                </a>
            </div>
        </div>
    </div>

    <!-- Tambah Parameter Baru -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h2 class="text-sm font-headline font-bold text-[#1E3A5F] uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
            Tambah Parameter Pemeriksaan Baru
        </h2>

        <form action="{{ route('form-builder.parameter.store', $machine->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            @csrf
            <input type="hidden" name="template_id" value="{{ $template->id }}">

            <!-- Nama Parameter -->
            <div class="sm:col-span-6">
                <label class="block text-xs font-semibold text-[#1E3A5F] mb-1">Nama Parameter *</label>
                <input type="text" name="name" required placeholder="Contoh: Suhu Bearing, Tekanan Oli, Tegangan Accu"
                       class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 shadow-sm p-2.5">
            </div>

            <!-- Syarat Teks Bebas -->
            <div class="sm:col-span-4">
                <label class="block text-xs font-semibold text-[#1E3A5F] mb-1">Syarat / Acuan (Teks Bebas)</label>
                <input type="text" name="requirement" placeholder="Contoh: < 35 °C, >80%, Baik, 3 - 5 Bar"
                       class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 shadow-sm p-2.5">
            </div>

            <!-- Submit Button -->
            <div class="sm:col-span-2 flex items-end">
                <button type="submit" 
                        class="w-full py-2.5 px-4 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm hover:shadow-emerald-600/20 transition">
                    + Tambah
                </button>
            </div>
        </form>
    </div>

    <!-- Parameter List Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-headline font-bold text-[#1E3A5F]">Daftar Parameter Aktif (Template v{{ $template->version }})</h2>
                <p class="text-xs text-slate-500 mt-0.5">Parameter ini yang akan tampil dan diisi oleh Teknisi di lapangan.</p>
            </div>
            <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                {{ $template->parameters->count() }} Parameter
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5 w-16 text-center">Urutan</th>
                        <th class="px-6 py-3.5">Nama Parameter</th>
                        <th class="px-6 py-3.5">Syarat (Acuan Teks Bebas)</th>
                        <th class="px-6 py-3.5 text-right w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($template->parameters as $idx => $param)
                        <tr class="hover:bg-slate-50/80 transition" x-data="{ editing: false }">
                            <td class="px-6 py-4 text-center font-bold text-slate-400">
                                {{ $idx + 1 }}
                            </td>
                            
                            <!-- Normal Mode -->
                            <td class="px-6 py-4 font-bold text-[#1E3A5F]" x-show="!editing">
                                {{ $param->name }}
                            </td>
                            <td class="px-6 py-4" x-show="!editing">
                                <span class="bg-[#FEF3C7] text-[#92400E] border border-amber-200 text-xs px-2.5 py-1 rounded-md font-semibold">
                                    {{ $param->requirement ?? 'Bebas' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2" x-show="!editing">
                                <button type="button" @click="editing = true" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900">
                                    Edit
                                </button>
                                <form action="{{ route('form-builder.parameter.destroy', $param->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus parameter ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800">
                                        Hapus
                                    </button>
                                </form>
                            </td>

                            <!-- Inline Edit Mode -->
                            <td colspan="3" class="px-6 py-3 bg-emerald-50/50" x-show="editing" style="display: none;">
                                <form action="{{ route('form-builder.parameter.update', $param->id) }}" method="POST" class="flex items-center gap-3">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $param->name }}" required
                                           class="text-xs rounded-lg border-slate-300 focus:ring-emerald-500 py-1.5 px-2.5 w-1/2">
                                    <input type="text" name="requirement" value="{{ $param->requirement }}" placeholder="Syarat..."
                                           class="text-xs rounded-lg border-slate-300 focus:ring-emerald-500 py-1.5 px-2.5 w-1/3">
                                    <button type="submit" class="text-xs px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-sm">
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
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                Belum ada parameter yang didaftarkan. Gunakan form di atas untuk menambahkan parameter pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
