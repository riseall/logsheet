@extends('layouts.app')

@section('title', 'Form Builder - Template Parameter Mesin')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-headline font-bold text-[#1E3A5F]">Form Builder Dinamis</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Kelola parameter pemeriksaan dinamis per unit mesin (Nama Parameter & Syarat teks bebas) tanpa coding.
                </p>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-semibold border border-emerald-200">
                    Admin & SPV Area
                </span>
            </div>
        </div>
    </div>

    <!-- Machine List Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-headline font-bold text-[#1E3A5F]">Daftar Mesin & Konfigurasi Form</h2>
            <span class="text-xs text-slate-400">Total {{ $machines->count() }} Mesin</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Kode & Nama Mesin</th>
                        <th class="px-6 py-3.5">Kategori</th>
                        <th class="px-6 py-3.5">Lokasi Gedung</th>
                        <th class="px-6 py-3.5">Versi Form</th>
                        <th class="px-6 py-3.5">Jumlah Parameter</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($machines as $m)
                        @php
                            $template = $m->latestTemplate;
                            $count = $template ? $template->parameters->count() : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-[#1E3A5F] block">{{ $m->name }}</span>
                                <span class="text-xs font-mono text-slate-400">{{ $m->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    {{ $m->kategori->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ $m->bangunan->name ?? '-' }} ({{ $m->bangunan->location ?? 'Area Pabrik' }})
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-[#1E3A5F]">
                                v{{ $template->version ?? 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold font-headline text-[#1E3A5F]">{{ $count }}</span>
                                <span class="text-xs text-slate-400">item</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('form-builder.edit', $m->id) }}" 
                                   class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition">
                                    ⚙️ Atur Parameter &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Belum ada mesin terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
