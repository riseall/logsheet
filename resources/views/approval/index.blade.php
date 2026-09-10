@extends('layouts.app')

@section('title', 'Antrian Approval Logsheet')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-headline font-bold text-[#1E3A5F]">Antrian Approval Logsheet</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Verifikasi dan persetujuan bertingkat (Teknisi &rarr; SPV &rarr; Manager). Satu keputusan untuk seluruh submission mesin.
                </p>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-900">
                    Role Aktif: <strong class="text-emerald-700 ml-1 font-bold">{{ $currentUser->role ?? 'Teknisi' }}</strong>
                </span>
            </div>
        </div>
    </div>

    <!-- Filter Tabs (CauseConnect Color Coding) -->
    <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-2 text-sm font-medium">
        <a href="{{ route('approval.index', ['tab' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg transition flex items-center space-x-2 {{ $tab === 'pending' ? 'bg-emerald-600 text-white font-bold shadow-sm shadow-emerald-600/30' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>Semua Pending</span>
            <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'pending' ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-100 text-slate-700' }}">
                {{ $counts['pending'] }}
            </span>
        </a>

        <a href="{{ route('approval.index', ['tab' => 'spv']) }}" 
           class="px-4 py-2 rounded-lg transition flex items-center space-x-2 {{ $tab === 'spv' ? 'bg-[#EA580C] text-white font-bold shadow-sm shadow-orange-500/30' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>Menunggu SPV</span>
            <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'spv' ? 'bg-orange-800 text-orange-100' : 'bg-slate-100 text-slate-700' }}">
                {{ $counts['spv'] }}
            </span>
        </a>

        <a href="{{ route('approval.index', ['tab' => 'manager']) }}" 
           class="px-4 py-2 rounded-lg transition flex items-center space-x-2 {{ $tab === 'manager' ? 'bg-[#1E3A5F] text-white font-bold shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>Menunggu Manager</span>
            <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'manager' ? 'bg-slate-800 text-slate-100' : 'bg-slate-100 text-slate-700' }}">
                {{ $counts['manager'] }}
            </span>
        </a>

        <a href="{{ route('approval.index', ['tab' => 'selesai']) }}" 
           class="px-4 py-2 rounded-lg transition flex items-center space-x-2 {{ $tab === 'selesai' ? 'bg-emerald-600 text-white font-bold shadow-sm shadow-emerald-600/30' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>Selesai (Final)</span>
            <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'selesai' ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-100 text-slate-700' }}">
                {{ $counts['selesai'] }}
            </span>
        </a>

        <a href="{{ route('approval.index', ['tab' => 'revisi']) }}" 
           class="px-4 py-2 rounded-lg transition flex items-center space-x-2 {{ $tab === 'revisi' ? 'bg-[#DC2626] text-white font-bold shadow-sm shadow-rose-500/30' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>Perlu Revisi</span>
            <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'revisi' ? 'bg-rose-800 text-rose-100' : 'bg-slate-100 text-slate-700' }}">
                {{ $counts['revisi'] }}
            </span>
        </a>
    </div>

    <!-- Table List -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal / Shift</th>
                        <th class="px-6 py-3.5">Unit Mesin</th>
                        <th class="px-6 py-3.5">Lokasi & Kategori</th>
                        <th class="px-6 py-3.5">Teknisi</th>
                        <th class="px-6 py-3.5">Status Saat Ini</th>
                        <th class="px-6 py-3.5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logsheets as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-[#1E3A5F] block">{{ $log->date ? $log->date->format('d/m/Y') : '-' }}</span>
                                <span class="text-xs text-slate-500">Shift {{ $log->shift }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-[#1E3A5F] block">{{ $log->mesin->name ?? '-' }}</span>
                                <span class="text-xs font-mono text-slate-400">{{ $log->mesin->code ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                <span class="font-semibold text-[#1E3A5F] block">{{ $log->mesin->bangunan->name ?? '-' }}</span>
                                <span class="text-slate-400">{{ $log->mesin->kategori->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-700">
                                {{ $log->teknisi->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($log->status === 'menunggu_spv')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEF3C7] text-[#92400E]">
                                        ⏳ Menunggu SPV
                                    </span>
                                @elseif($log->status === 'menunggu_manager')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                                        👔 Menunggu Manager
                                    </span>
                                @elseif($log->status === 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                                        &check; Selesai
                                    </span>
                                @elseif($log->status === 'perlu_revisi')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B]">
                                        &excl; Perlu Revisi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('approval.review', $log->id) }}" 
                                   class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm hover:shadow-emerald-600/20">
                                    Review &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada logsheet pada kategori tab ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logsheets->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logsheets->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
