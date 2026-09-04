@extends('layouts.app')

@section('title', 'Review Approval - ' . ($logsheet->mesin->name ?? 'Mesin'))

@section('content')
<div class="space-y-6 max-w-5xl mx-auto" x-data="{ rejectModal: false, approveModal: false }">

    <!-- Header info -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="text-xs text-slate-400 mb-1 flex items-center space-x-2">
                    <a href="{{ route('approval.index') }}" class="text-emerald-700 hover:underline">&larr; Kembali ke Antrian</a>
                    <span>&bull;</span>
                    <span>Review Submission #{{ $logsheet->id }}</span>
                </nav>
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-headline font-bold text-[#1E3A5F]">{{ $logsheet->mesin->name ?? '-' }}</h1>
                    @if($logsheet->status === 'menunggu_spv')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#FEF3C7] text-[#92400E]">Menunggu SPV</span>
                    @elseif($logsheet->status === 'menunggu_manager')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF]">Menunggu Manager</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Gedung: {{ $logsheet->mesin->bangunan->name ?? '-' }} &bull;
                    Tanggal: <strong>{{ $logsheet->date ? $logsheet->date->format('d/m/Y') : '-' }}</strong> &bull;
                    Shift: <strong>Shift {{ $logsheet->shift }}</strong> &bull;
                    Teknisi: <strong>{{ $logsheet->teknisi->name ?? '-' }}</strong>
                </p>
            </div>

            <!-- Single-decision Action Buttons (CauseConnect Action vs Destructive) -->
            @if(in_array($logsheet->status, ['menunggu_spv', 'menunggu_manager']))
                <div class="flex items-center space-x-3">
                    <button @click="rejectModal = true" 
                            class="px-5 py-2.5 rounded-lg text-sm font-semibold text-[#DC2626] bg-rose-50 hover:bg-rose-100 border border-rose-200 transition">
                        ✕ Tolak / Revisi
                    </button>
                    <button @click="approveModal = true" 
                            class="px-6 py-2.5 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/40 transition">
                        ✓ Setujui (Approve)
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Parameter Results Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-base font-headline font-bold text-[#1E3A5F]">Pemeriksaan Seluruh Parameter</h2>
            <p class="text-xs text-slate-500 mt-0.5">Tinjau seluruh data nilai pengukuran dan kondisi sebelum mengambil keputusan.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5 w-12 text-center">No</th>
                        <th class="px-6 py-3.5">Parameter Pemeriksaan</th>
                        <th class="px-6 py-3.5">Syarat (Pedoman)</th>
                        <th class="px-6 py-3.5 font-bold text-[#1E3A5F]">Nilai Isian Teknisi</th>
                        <th class="px-6 py-3.5">Status Kondisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($logsheet->details as $idx => $d)
                        <tr class="{{ $d->condition_status === 'perlu_perhatian' ? 'bg-rose-50/40' : 'hover:bg-slate-50/60' }} transition">
                            <td class="px-6 py-4 text-center font-semibold text-slate-400">
                                {{ $idx + 1 }}
                            </td>
                            <td class="px-6 py-4 font-bold text-[#1E3A5F]">
                                {{ $d->parameter->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                <span class="bg-slate-100 px-2.5 py-1 rounded-md">
                                    {{ $d->parameter->requirement ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-base text-[#1E3A5F]">
                                {{ $d->value }}
                            </td>
                            <td class="px-6 py-4">
                                @if($d->condition_status === 'baik')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534] border border-emerald-200">
                                        &check; Baik
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B] border border-rose-300">
                                        ⚠️ Perlu Perhatian
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Alur Approval Log -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-headline font-bold text-[#1E3A5F] mb-4 pb-2 border-b border-slate-100">
            Riwayat Alur Keputusan
        </h2>
        <div class="space-y-4 text-sm">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs mt-0.5">
                    1
                </div>
                <div>
                    <p class="font-semibold text-[#1E3A5F]">
                        Disubmit oleh: {{ $logsheet->teknisi->name ?? '-' }} (Teknisi)
                    </p>
                    <p class="text-xs text-slate-400">
                        {{ $logsheet->created_at ? $logsheet->created_at->format('d M Y, H:i') : '-' }}
                    </p>
                </div>
            </div>

            @foreach($logsheet->approvalLogs as $log)
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-full {{ $log->action === 'approve' ? 'bg-[#DCFCE7] text-[#166534]' : 'bg-[#FEE2E2] text-[#991B1B]' }} flex items-center justify-center font-bold text-xs mt-0.5">
                        {{ $log->action === 'approve' ? '✓' : '✕' }}
                    </div>
                    <div>
                        <p class="font-semibold text-[#1E3A5F]">
                            {{ $log->action === 'approve' ? 'Disetujui' : 'Ditolak' }} oleh 
                            <span class="capitalize font-bold">{{ $log->role }}</span> ({{ $log->user->name ?? '-' }})
                        </p>
                        <p class="text-xs text-slate-400">{{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}</p>
                        @if($log->note)
                            <div class="mt-1 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                <strong>Catatan:</strong> {{ $log->note }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Approve Modal -->
    <div x-show="approveModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" style="display: none;">
        <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl border border-slate-200">
            <h3 class="text-lg font-headline font-bold text-[#1E3A5F] mb-2">Konfirmasi Persetujuan (Approve)</h3>
            <p class="text-xs text-slate-500 mb-4">
                Satu keputusan untuk seluruh parameter pada submission ini.
            </p>

            <form action="{{ route('approval.approve', $logsheet->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-[#1E3A5F] mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea name="note" rows="3" placeholder="Contoh: Kondisi mesin dalam batas toleransi normal."
                              class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 shadow-sm p-3"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="approveModal = false" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition">
                        ✓ Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" style="display: none;">
        <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl border border-slate-200">
            <h3 class="text-lg font-headline font-bold text-[#DC2626] mb-2">Tolak / Minta Revisi</h3>
            <p class="text-xs text-slate-500 mb-4">
                @if($logsheet->status === 'menunggu_manager')
                    Sesuai PRD §4, penolakan oleh Manager akan dikembalikan terlebih dahulu ke antrian Supervisor.
                @else
                    Penolakan oleh SPV akan langsung dikembalikan ke Teknisi untuk pengisian ulang.
                @endif
            </p>

            <form action="{{ route('approval.reject', $logsheet->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-[#1E3A5F] mb-1">Catatan Alasan Penolakan / Revisi *</label>
                    <textarea name="note" rows="3" required placeholder="Tuliskan alasan penolakan secara jelas..."
                              class="w-full text-sm rounded-lg border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 shadow-sm p-3"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="rejectModal = false" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-lg text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-sm transition">
                        ✕ Konfirmasi Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
