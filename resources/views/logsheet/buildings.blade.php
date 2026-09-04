@extends('layouts.app')

@section('title', 'Pilih Bangunan - ' . $category->name)

@section('content')
<div class="space-y-6">

    <!-- Header & Step Indicator -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="text-xs text-slate-400 mb-1 flex items-center space-x-2">
                    <a href="{{ route('logsheet.index') }}" class="text-emerald-700 hover:underline">Kategori: {{ $category->name }}</a>
                    <span>&bull;</span>
                    <span class="text-emerald-700 font-semibold">Langkah 2 dari 3</span>
                </nav>
                <h1 class="text-2xl font-headline font-bold text-[#1E3A5F]">Pilih Gedung / Bangunan</h1>
                <p class="text-xs text-slate-500 mt-1">Daftar gedung yang memiliki unit mesin kategori <strong class="text-[#1E3A5F]">{{ $category->name }}</strong>.</p>
            </div>
            <div class="flex items-center space-x-2 text-xs">
                <a href="{{ route('logsheet.index') }}" class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center hover:bg-emerald-200 transition">&check;</a>
                <span class="text-slate-500">Kategori</span>
                <span class="text-slate-300">&rarr;</span>
                <span class="w-7 h-7 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center shadow-sm shadow-emerald-200">2</span>
                <span class="font-bold text-[#1E3A5F]">Bangunan</span>
                <span class="text-slate-300">&rarr;</span>
                <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 font-semibold flex items-center justify-center">3</span>
                <span class="text-slate-400">Mesin</span>
            </div>
        </div>
    </div>

    <!-- Building Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($buildings as $b)
            <a href="{{ route('logsheet.machines', [$category->id, $b->id]) }}" 
               class="group bg-white p-6 rounded-xl border border-slate-200 hover:border-emerald-600 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="w-12 h-12 rounded-lg bg-slate-100 text-slate-700 group-hover:bg-emerald-600 group-hover:text-white transition flex items-center justify-center font-headline font-bold text-sm">
                            {{ $b->code }}
                        </span>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 font-semibold">
                            {{ $b->mesins_count }} Unit Mesin
                        </span>
                    </div>
                    <h2 class="text-lg font-headline font-bold text-[#1E3A5F] group-hover:text-emerald-700 transition">{{ $b->name }}</h2>
                    <p class="text-xs text-slate-500 mt-2 flex items-center">
                        <span class="mr-1">📍</span> {{ $b->location ?? 'Area Pabrik' }}
                    </p>
                </div>

                <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 group-hover:text-[#1E3A5F]">Pilih Gedung Ini</span>
                    <div class="w-8 h-8 rounded-full bg-slate-50 group-hover:bg-emerald-50 text-slate-400 group-hover:text-emerald-700 flex items-center justify-center transition">
                        &rarr;
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-3 bg-white p-12 rounded-xl border border-slate-200 text-center">
                <p class="text-slate-500 text-sm">Belum ada mesin untuk kategori {{ $category->name }} di gedung manapun.</p>
                <div class="mt-4">
                    <a href="{{ route('logsheet.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">&larr; Kembali ke Kategori</a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
