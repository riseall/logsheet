@extends('layouts.app')

@section('title', 'Dashboard - Logsheet Monitoring Mesin')

@section('content')
    <div class="space-y-8">

        <!-- Welcome & Hero Section (CauseConnect Design: Trust Navy to Action Green) -->
        <div
            class="bg-gradient-to-r from-[#1E3A5F] via-[#0D4B3E] to-[#059669] rounded-3xl p-8 sm:p-10 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/15 text-emerald-100 border border-white/20 mb-4 backdrop-blur-sm">
                    Sistem Logsheet Digital v1.3
                </span>
                <h1 class="text-3xl sm:text-4xl font-headline font-extrabold tracking-tight text-white leading-tight">
                    Monitoring Kondisi Mesin Produksi
                </h1>
                <p class="mt-3 text-sm sm:text-base text-emerald-50/90 leading-relaxed font-normal">
                    Pencatatan harian parameter mesin (HVAC, ME, CU).
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('logsheet.index') }}"
                        class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-900/30 hover:shadow-lg transition">
                        📝 Mulai Isi Logsheet
                    </a>
                    @if ($currentUser && $currentUser->isTeknisi())
                        @if (($myPendingRevision ?? 0) > 0)
                            <a href="{{ route('laporan.index') }}"
                                class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold bg-rose-600 hover:bg-rose-700 text-white shadow-md shadow-rose-900/30 transition animate-pulse">
                                ⚠️ Perlu Revisi ({{ $myPendingRevision }})
                            </a>
                        @endif
                    @else
                        @php
                            $myQueueCount =
                                $currentUser && $currentUser->isManager()
                                    ? $pendingManager
                                    : ($currentUser && $currentUser->isSupervisor()
                                        ? $pendingSpv
                                        : $pendingSpv + $pendingManager);
                        @endphp
                        <a href="{{ route('approval.index') }}"
                            class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold bg-white/10 hover:bg-white/20 text-white transition border border-white/25 backdrop-blur-sm">
                            Antrian Approval ({{ $myQueueCount }})
                        </a>

                    @endif
                </div>
            </div>

            <!-- Decorative subtle background circle -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-emerald-500/10 pointer-events-none"></div>
        </div>

        <!-- Stat Cards (CauseConnect Metrics with Role-Based Context) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- 1. Total Mesin Aktif (Semua Role) -->
            <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Mesin Aktif</p>
                        <p class="text-3xl font-headline font-extrabold text-[#1E3A5F] mt-2">{{ $totalMachines }}</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl">
                        🏭
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-500 font-medium">
                    Terbagi di {{ $categories->count() }} kategori mesin
                </div>
            </div>

            @if ($currentUser && $currentUser->isTeknisi())
                <!-- 2. Logsheet Saya Hari Ini (Khusus Teknisi) -->
                <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Logsheet Saya Hari Ini
                            </p>
                            <p class="text-3xl font-headline font-extrabold text-emerald-700 mt-2">
                                {{ $mySubmittedToday ?? 0 }}</p>
                        </div>
                        <div
                            class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl">
                            📝
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-500 font-medium">
                        Submission yang sudah Anda kirim
                    </div>
                </div>

                <!-- 3. Perlu Revisi Saya (Khusus Teknisi) -->
                <div
                    class="bg-white p-6 rounded-xl border {{ ($myPendingRevision ?? 0) > 0 ? 'border-rose-300 bg-rose-50/30' : 'border-slate-200/80' }} shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p
                                class="text-xs font-semibold {{ ($myPendingRevision ?? 0) > 0 ? 'text-rose-700 font-bold' : 'text-slate-500' }} uppercase tracking-wider">
                                Perlu Revisi</p>
                            <p
                                class="text-3xl font-headline font-extrabold {{ ($myPendingRevision ?? 0) > 0 ? 'text-[#DC2626]' : 'text-slate-700' }} mt-2">
                                {{ $myPendingRevision ?? 0 }}</p>
                        </div>
                        <div
                            class="w-12 h-12 rounded-lg {{ ($myPendingRevision ?? 0) > 0 ? 'bg-rose-100 text-[#DC2626]' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center text-xl">
                            🔄
                        </div>
                    </div>
                    <div
                        class="mt-4 text-xs {{ ($myPendingRevision ?? 0) > 0 ? 'text-rose-600 font-semibold' : 'text-slate-500' }}">
                        {{ ($myPendingRevision ?? 0) > 0 ? 'Perlu tindakan koreksi segera' : 'Tidak ada revisi pending' }}
                    </div>
                </div>
            @elseif($currentUser && $currentUser->isManager())
                <!-- 2. Menunggu Approval Manager (Prioritas Utama Manager) -->
                <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Menunggu Manager</p>
                            <p class="text-3xl font-headline font-extrabold text-[#1E3A5F] mt-2">{{ $pendingManager }}</p>
                        </div>
                        <div
                            class="w-12 h-12 rounded-lg bg-slate-100 text-[#1E3A5F] flex items-center justify-center text-xl">
                            👔
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-500 font-medium">
                        Menunggu approval final Anda
                    </div>
                </div>

                <!-- 3. Menunggu SPV (Info Proses Awal) -->
                <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Menunggu SPV</p>
                            <p class="text-3xl font-headline font-extrabold text-[#EA580C] mt-2">{{ $pendingSpv }}</p>
                        </div>
                        <div
                            class="w-12 h-12 rounded-lg bg-orange-50 text-[#EA580C] flex items-center justify-center text-xl">
                            ⏳
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-500 font-medium">
                        Sedang di verifikasi tahap SPV
                    </div>
                </div>
            @else
                <!-- 2. Pending SPV Approval (Supervisor & Admin) -->
                <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Menunggu SPV</p>
                            <p class="text-3xl font-headline font-extrabold text-[#EA580C] mt-2">{{ $pendingSpv }}</p>
                        </div>
                        <div
                            class="w-12 h-12 rounded-lg bg-orange-50 text-[#EA580C] flex items-center justify-center text-xl">
                            ⏳
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-500 font-medium">
                        Menunggu verifikasi Supervisor
                    </div>
                </div>

                <!-- 3. Pending Manager Approval (Supervisor & Admin) -->
                <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Menunggu Manager</p>
                            <p class="text-3xl font-headline font-extrabold text-[#1E3A5F] mt-2">{{ $pendingManager }}</p>
                        </div>
                        <div
                            class="w-12 h-12 rounded-lg bg-slate-100 text-[#1E3A5F] flex items-center justify-center text-xl">
                            👔
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-500 font-medium">
                        Menunggu approval final Manager
                    </div>
                </div>
            @endif

            <!-- 4. Perlu Perhatian / Warning (Semua Role) -->
            <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Perlu Perhatian</p>
                        <p class="text-3xl font-headline font-extrabold text-[#DC2626] mt-2">{{ $needAttentionToday }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-rose-50 text-[#DC2626] flex items-center justify-center text-xl">
                        ⚠️
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-500 font-medium">
                    Parameter kondisi abnormal hari ini
                </div>
            </div>
        </div>

        <!-- Quick Navigation by Category Cards -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-headline font-bold text-[#1E3A5F]">Kategori Mesin Produksi</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pilih kategori untuk mulai mengisi logsheet mesin</p>
                </div>
                <a href="{{ route('logsheet.index') }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @forelse($categories as $cat)
                    <a href="{{ route('logsheet.buildings', $cat->id) }}"
                        class="group bg-white p-6 rounded-xl border border-slate-200 hover:border-emerald-600 hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div
                                class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-800 group-hover:bg-emerald-600 group-hover:text-white transition flex items-center justify-center font-bold text-2xl mb-4 shadow-sm">
                                <x-category-icon :code="$cat->code" class="w-6 h-6" />
                            </div>
                            <h3
                                class="text-base font-headline font-bold text-[#1E3A5F] group-hover:text-emerald-700 transition">
                                {{ $cat->name }}</h3>
                            <p class="text-xs text-slate-500 mt-1">Kode Kategori: {{ $cat->code }}</p>
                        </div>
                        <div
                            class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                            <span class="font-medium">{{ $cat->mesins_count }} Unit Mesin</span>
                            <span
                                class="text-emerald-700 font-semibold group-hover:translate-x-1 transition-transform inline-flex items-center">
                                Pilih &rarr;
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 bg-white p-8 rounded-xl text-center text-slate-500 border border-slate-200">
                        Belum ada kategori mesin. Jalankan seeder data awal.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Submissions Table (CauseConnect List & Badge Styling) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-headline font-bold text-[#1E3A5F]">Submission Logsheet Terbaru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Histori pencatatan logsheet yang baru masuk</p>
                </div>
                <a href="{{ route('laporan.index') }}"
                    class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                    Buka Semua Rekap &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Tanggal & Shift</th>
                            <th class="px-6 py-3.5">Mesin</th>
                            <th class="px-6 py-3.5">Lokasi</th>
                            <th class="px-6 py-3.5">Teknisi</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentLogsheets as $log)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4">
                                    <span
                                        class="font-semibold text-[#1E3A5F] block">{{ $log->date ? $log->date->format('d M Y') : '-' }}</span>
                                    <span class="text-xs text-slate-500">Shift {{ $log->shift }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-[#1E3A5F] block">{{ $log->mesin->name ?? '-' }}</span>
                                    <span class="text-xs text-slate-400 font-mono">{{ $log->mesin->code ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600">
                                    {{ $log->mesin->bangunan->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600">
                                    {{ $log->teknisi->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($log->status === 'menunggu_spv')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FEF3C7] text-[#92400E]">
                                            Menunggu SPV
                                        </span>
                                    @elseif($log->status === 'menunggu_manager')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                                            Menunggu Manager
                                        </span>
                                    @elseif($log->status === 'selesai')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                                            Selesai
                                        </span>
                                    @elseif($log->status === 'perlu_revisi')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B]">
                                            Perlu Revisi
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('logsheet.show', $log->id) }}"
                                        class="inline-flex items-center text-xs font-semibold text-emerald-700 hover:text-emerald-900">
                                        Detail &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-sm">
                                    Belum ada logsheet yang dicatat. Klik "Mulai Isi Logsheet" untuk membuat logsheet
                                    pertama.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
