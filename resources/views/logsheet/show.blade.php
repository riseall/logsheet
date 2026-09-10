@extends('layouts.app')

@section('title', 'Detail Logsheet - ' . ($logsheet->mesin->name ?? 'Mesin'))

@section('content')
    <div class="space-y-6 max-w-5xl mx-auto">

        <!-- Header Card -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <nav class="text-xs text-slate-400 mb-1 flex items-center space-x-2">
                        <a href="{{ route('home') }}" class="text-emerald-700 hover:underline">Home</a>
                        <span>&bull;</span>
                        <span>Detail Logsheet #{{ $logsheet->id }}</span>
                    </nav>
                    <div class="flex items-center space-x-3">
                        <h1 class="text-2xl font-headline font-bold text-[#1E3A5F]">{{ $logsheet->mesin->name ?? '-' }}</h1>
                        <!-- Status Badge -->
                        @if ($logsheet->status === 'menunggu_spv')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#FEF3C7] text-[#92400E]">
                                Menunggu SPV
                            </span>
                        @elseif($logsheet->status === 'menunggu_manager')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                                Menunggu Manager
                            </span>
                        @elseif($logsheet->status === 'selesai')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                                &check; Selesai (Final)
                            </span>
                        @elseif($logsheet->status === 'perlu_revisi')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B]">
                                &excl; Perlu Revisi
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">
                                Draft
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Gedung: <strong class="text-[#1E3A5F]">{{ $logsheet->mesin->bangunan->name ?? '-' }}</strong> &bull;
                        Kategori: <strong class="text-[#1E3A5F]">{{ $logsheet->mesin->kategori->name ?? '-' }}</strong>
                    </p>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('laporan.print', $logsheet->id) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold text-[#1E3A5F] bg-slate-100 hover:bg-slate-200 transition">
                        🖨️ Cetak
                    </a>
                    @if (auth()->user()->is_spv || auth()->user()->is_manager || auth()->user()->is_admin)
                        @if (in_array($logsheet->status, ['menunggu_spv', 'menunggu_manager']))
                            <a href="{{ route('approval.review', $logsheet->id) }}"
                                class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm hover:shadow-emerald-600/20">
                                Review Approval &rarr;
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Metadata Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center sm:text-left">
                <span class="text-xs text-slate-400 block">Tanggal Pengisian</span>
                <span class="text-sm font-bold text-[#1E3A5F] mt-0.5 block">
                    {{ $logsheet->date ? $logsheet->date->format('d F Y') : '-' }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center sm:text-left">
                <span class="text-xs text-slate-400 block">Shift Kerja</span>
                <span class="text-sm font-bold text-[#1E3A5F] mt-0.5 block">
                    Shift {{ $logsheet->shift }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center sm:text-left">
                <span class="text-xs text-slate-400 block">Teknisi Pelaksana</span>
                <span class="text-sm font-bold text-[#1E3A5F] mt-0.5 block">
                    {{ $logsheet->teknisi->name ?? '-' }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center sm:text-left">
                <span class="text-xs text-slate-400 block">Versi Form</span>
                <span class="text-sm font-bold text-[#1E3A5F] mt-0.5 block">
                    v{{ $logsheet->template->version ?? 1 }}
                </span>
            </div>
        </div>

        <!-- Details Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-base font-headline font-bold text-[#1E3A5F]">Hasil Pemeriksaan Parameter</h2>
                <p class="text-xs text-slate-500 mt-0.5">Nilai riil dan status kondisi yang dilaporkan</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5 w-12 text-center">No</th>
                            <th class="px-6 py-3.5">Parameter</th>
                            <th class="px-6 py-3.5">Syarat (Acuan)</th>
                            <th class="px-6 py-3.5">Nilai Riil</th>
                            <th class="px-6 py-3.5">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logsheet->details as $index => $det)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 text-center font-semibold text-slate-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-[#1E3A5F]">
                                    {{ $det->parameter->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600">
                                    <span class="bg-slate-100 px-2.5 py-1 rounded-md">
                                        {{ $det->parameter->requirement ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-[#1E3A5F]">
                                    {{ $det->value }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($det->condition_status === 'baik')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534] border border-emerald-200">
                                            &check; Baik
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B] border border-rose-200">
                                            &excl; Perlu Perhatian
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                    Tidak ada data parameter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Audit Trail & Approval Logs -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-base font-headline font-bold text-[#1E3A5F] mb-4 pb-2 border-b border-slate-100">
                Riwayat Alur Approval & Revisi
            </h2>

            <div class="space-y-4">
                <!-- Submit Stage -->
                <div class="flex items-start space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs mt-0.5">
                        1
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#1E3A5F]">
                            Disubmit oleh Teknisi: <span
                                class="text-emerald-700 font-bold">{{ $logsheet->teknisi->name ?? '-' }}</span>
                        </p>
                        <p class="text-xs text-slate-400">
                            {{ $logsheet->created_at ? $logsheet->created_at->format('d M Y, H:i') : '-' }}
                        </p>
                    </div>
                </div>

                <!-- Approval Logs list -->
                @foreach ($logsheet->approvalLogs as $log)
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-8 h-8 rounded-full {{ $log->action === 'approve' ? 'bg-[#DCFCE7] text-[#166534]' : 'bg-[#FEE2E2] text-[#991B1B]' }} flex items-center justify-center font-bold text-xs mt-0.5">
                            {{ $log->action === 'approve' ? '✓' : '✕' }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#1E3A5F]">
                                {{ $log->action === 'approve' ? 'Disetujui' : 'Ditolak' }} oleh
                                <span class="capitalize font-bold">{{ $log->role }}</span>
                                ({{ $log->user->name ?? '-' }})
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}
                            </p>
                            @if ($log->note)
                                <div
                                    class="mt-1 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-100 max-w-lg">
                                    <span class="font-semibold text-[#1E3A5F]">Catatan:</span> {{ $log->note }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
