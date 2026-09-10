@extends('layouts.app')

@section('title', 'Pilih Kategori - Logsheet Monitoring')

@section('content')
    <div class="space-y-6">

        <!-- Header & Step Indicator -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <nav class="text-xs text-slate-400 mb-1 flex items-center space-x-2">
                        <span class="text-emerald-700 font-semibold">Langkah 1 dari 3</span>
                        <span>&bull;</span>
                        <span>Isi Logsheet Baru</span>
                    </nav>
                    <h1 class="text-2xl font-headline font-bold text-[#1E3A5F]">Pilih Kategori Mesin</h1>
                    <p class="text-xs text-slate-500 mt-1">Pilih kategori mesin produksi yang akan dilakukan pemeriksaan
                        berkala.</p>
                </div>
                <div class="flex items-center space-x-2 text-xs">
                    <span
                        class="w-7 h-7 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center shadow-sm shadow-emerald-200">1</span>
                    <span class="font-bold text-[#1E3A5F]">Kategori</span>
                    <span class="text-slate-300">&rarr;</span>
                    <span
                        class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 font-semibold flex items-center justify-center">2</span>
                    <span class="text-slate-400">Bangunan</span>
                    <span class="text-slate-300">&rarr;</span>
                    <span
                        class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 font-semibold flex items-center justify-center">3</span>
                    <span class="text-slate-400">Mesin</span>
                </div>
            </div>
        </div>

        <!-- Category Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($categories as $cat)
                <a href="{{ route('logsheet.buildings', $cat->id) }}"
                    class="group bg-white p-6 rounded-xl border border-slate-200 hover:border-emerald-600 hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-800 group-hover:bg-emerald-600 group-hover:text-white transition flex items-center justify-center font-bold text-2xl mb-4 shadow-sm">
                                <x-category-icon :code="$cat->code" class="w-6 h-6" />
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-medium">
                                {{ $cat->mesins_count }} Unit Mesin
                            </span>
                        </div>
                        <h2 class="text-lg font-headline font-bold text-[#1E3A5F] group-hover:text-emerald-700 transition">
                            {{ $cat->name }}</h2>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Pemeriksaan parameter mesin di bawah divisi {{ $cat->name }}.
                        </p>
                    </div>

                    <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 group-hover:text-[#1E3A5F]">Lihat Daftar
                            Bangunan</span>
                        <div
                            class="w-8 h-8 rounded-full bg-slate-50 group-hover:bg-emerald-50 text-slate-400 group-hover:text-emerald-700 flex items-center justify-center transition">
                            &rarr;
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 bg-white p-12 rounded-xl border border-slate-200 text-center">
                    <p class="text-slate-500 text-sm">Belum ada kategori mesin. Jalankan seeder database terlebih dahulu.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
