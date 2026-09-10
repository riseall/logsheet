@extends('layouts.app')

@section('title', 'Rekap Histori Laporan Logsheet')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-headline font-bold text-[#1E3A5F]">Rekap Histori Logsheet</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Laporan pemantauan kondisi mesin, audit trail status approval, dan histori parameter harian.
                </p>
            </div>
            <div>
                <x-button onclick="window.print()" variant="secondary" size="sm">
                    🖨️ Cetak Ringkasan
                </x-button>
            </div>
        </div>
    </div>

    <!-- Multi-Filter Card -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <form action="{{ route('laporan.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                <!-- Start Date -->
                <div>
                    <label class="block text-[11px] font-semibold text-[#1E3A5F] mb-1">Dari Tanggal</label>
                    <x-input type="date" name="start_date" value="{{ request('start_date') }}" />
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-[11px] font-semibold text-[#1E3A5F] mb-1">Sampai Tanggal</label>
                    <x-input type="date" name="end_date" value="{{ request('end_date') }}" />
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-[11px] font-semibold text-[#1E3A5F] mb-1">Kategori Mesin</label>
                    <x-select name="category_id">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <!-- Bangunan -->
                <div>
                    <label class="block text-[11px] font-semibold text-[#1E3A5F] mb-1">Gedung / Bangunan</label>
                    <x-select name="building_id">
                        <option value="">Semua Gedung</option>
                        @foreach($buildings as $b)
                            <option value="{{ $b->id }}" {{ request('building_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <!-- Shift -->
                <div>
                    <label class="block text-[11px] font-semibold text-[#1E3A5F] mb-1">Shift Kerja</label>
                    <x-select name="shift">
                        <option value="">Semua Shift</option>
                        <option value="1" {{ request('shift') == '1' ? 'selected' : '' }}>Shift 1</option>
                        <option value="2" {{ request('shift') == '2' ? 'selected' : '' }}>Shift 2</option>
                        <option value="3" {{ request('shift') == '3' ? 'selected' : '' }}>Shift 3</option>
                    </x-select>
                </div>

                <!-- Status Approval -->
                <div>
                    <label class="block text-[11px] font-semibold text-[#1E3A5F] mb-1">Status Approval</label>
                    <x-select name="status">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="menunggu_spv" {{ request('status') == 'menunggu_spv' ? 'selected' : '' }}>Menunggu SPV</option>
                        <option value="menunggu_manager" {{ request('status') == 'menunggu_manager' ? 'selected' : '' }}>Menunggu Manager</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="perlu_revisi" {{ request('status') == 'perlu_revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                    </x-select>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <x-button href="{{ route('laporan.index') }}" variant="ghost" size="xs">
                    Reset Filter
                </x-button>
                <x-button type="submit" variant="teal" size="xs">
                    Terapkan Filter
                </x-button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal / Shift</th>
                        <th class="px-6 py-3.5">Mesin</th>
                        <th class="px-6 py-3.5">Lokasi</th>
                        <th class="px-6 py-3.5">Teknisi</th>
                        <th class="px-6 py-3.5">Ringkasan Kondisi</th>
                        <th class="px-6 py-3.5">Status Approval</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logsheets as $log)
                        @php
                            $baikCount = $log->details->where('condition_status', 'baik')->count();
                            $warningCount = $log->details->where('condition_status', 'perlu_perhatian')->count();
                        @endphp
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
                                {{ $log->mesin->bangunan->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-700">
                                {{ $log->teknisi->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-1.5 text-xs">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[#DCFCE7] text-[#166534] font-semibold border border-emerald-200">
                                        {{ $baikCount }} Baik
                                    </span>
                                    @if($warningCount > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[#FEE2E2] text-[#991B1B] font-bold border border-rose-200">
                                            {{ $warningCount }} Abnormal
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->status === 'menunggu_spv')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FEF3C7] text-[#92400E]">Menunggu SPV</span>
                                @elseif($log->status === 'menunggu_manager')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF]">Menunggu Manager</span>
                                @elseif($log->status === 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">&check; Selesai</span>
                                @elseif($log->status === 'perlu_revisi')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B]">&excl; Perlu Revisi</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('logsheet.show', $log->id) }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900">
                                    Detail
                                </a>
                                <a href="{{ route('laporan.print', $log->id) }}" target="_blank" class="text-xs font-semibold text-slate-500 hover:text-[#1E3A5F]">
                                    Print
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada logsheet yang sesuai dengan filter.
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
