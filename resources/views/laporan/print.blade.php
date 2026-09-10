<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Logsheet_{{ $logsheet->mesin->code ?? 'MESIN' }}_{{ $logsheet->date ? $logsheet->date->format('Ymd') : '' }}
    </title>

    <!-- Compiled Styles (TailwindCSS & Self-hosted Fonts) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            color: #1E3A5F;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Nunito', sans-serif;
            color: #1E3A5F;
        }

        .font-mono {
            font-family: 'Roboto Mono', monospace;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-white text-[#1E3A5F] font-sans p-8 max-w-4xl mx-auto">

    <!-- Print Action Bar (Hidden on print) -->
    <div class="no-print flex justify-between items-center bg-slate-100 p-4 rounded-xl mb-6 border border-slate-200">
        <span class="text-xs font-semibold text-slate-700">Preview Dokumen Cetak Resmi (CauseConnect Design)</span>
        <div class="space-x-2">
            <button onclick="window.close()"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-300 hover:bg-slate-50">
                Tutup
            </button>
            <button onclick="window.print()"
                class="px-4 py-1.5 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm">
                🖨️ Cetak Sekarang
            </button>
        </div>
    </div>

    <!-- Official Header -->
    <div class="border-b-2 border-[#1E3A5F] pb-4 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-xl font-headline font-extrabold tracking-tight text-[#1E3A5F] uppercase">
                    LAPORAN PEMERIKSAAN & PERAWATAN HARIAN MESIN
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Departemen Utility &bull; Sistem Monitoring Logsheet
                </p>
            </div>
            <div class="text-right text-xs">
                <span class="font-mono font-bold block text-[#1E3A5F]">NO:
                    LS-{{ str_pad($logsheet->id, 6, '0', STR_PAD_LEFT) }}</span>
                <span class="text-slate-500">Status: <strong
                        class="uppercase text-[#1E3A5F]">{{ str_replace('_', ' ', $logsheet->status) }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Info Table -->
    <div class="grid grid-cols-2 gap-4 text-xs mb-6 border border-slate-200 p-4 rounded-lg bg-slate-50">
        <div class="space-y-1">
            <div><span class="text-slate-500 w-28 inline-block">Nama Mesin:</span> <strong
                    class="text-[#1E3A5F]">{{ $logsheet->mesin->name ?? '-' }}</strong></div>
            <div><span class="text-slate-500 w-28 inline-block">Kode Unit:</span> <strong
                    class="font-mono text-[#1E3A5F]">{{ $logsheet->mesin->code ?? '-' }}</strong></div>
            <div><span class="text-slate-500 w-28 inline-block">Kategori:</span> <strong
                    class="text-[#1E3A5F]">{{ $logsheet->mesin->kategori->name ?? '-' }}</strong></div>
            <div><span class="text-slate-500 w-28 inline-block">Lokasi Gedung:</span> <strong
                    class="text-[#1E3A5F]">{{ $logsheet->mesin->bangunan->name ?? '-' }}
                    ({{ $logsheet->mesin->bangunan->location ?? '-' }})</strong></div>
        </div>
        <div class="space-y-1">
            <div><span class="text-slate-500 w-28 inline-block">Tanggal Periksa:</span> <strong
                    class="text-[#1E3A5F]">{{ $logsheet->date ? $logsheet->date->format('d F Y') : '-' }}</strong></div>
            <div><span class="text-slate-500 w-28 inline-block">Shift Kerja:</span> <strong class="text-[#1E3A5F]">Shift
                    {{ $logsheet->shift }}</strong></div>
            <div><span class="text-slate-500 w-28 inline-block">Teknisi Petugas:</span> <strong
                    class="text-[#1E3A5F]">{{ $logsheet->teknisi->name ?? '-' }}</strong></div>
        </div>
    </div>

    <!-- Parameter Results Table -->
    <div class="mb-8">
        <h2 class="text-xs font-headline font-bold uppercase tracking-wider text-[#1E3A5F] mb-2">Parameter Pemeriksaan &
            Kondisi Fisik</h2>
        <table class="w-full border-collapse border border-slate-300 text-xs">
            <thead>
                <tr class="bg-slate-100 text-[#1E3A5F] text-left">
                    <th class="border border-slate-300 px-3 py-2 w-10 text-center">No</th>
                    <th class="border border-slate-300 px-3 py-2">Parameter Pemeriksaan</th>
                    <th class="border border-slate-300 px-3 py-2">Syarat / Acuan Standar</th>
                    <th class="border border-slate-300 px-3 py-2 w-36">Nilai Pengukuran</th>
                    <th class="border border-slate-300 px-3 py-2 w-28 text-center">Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logsheet->details as $i => $d)
                    <tr>
                        <td class="border border-slate-300 px-3 py-2 text-center">{{ $i + 1 }}</td>
                        <td class="border border-slate-300 px-3 py-2 font-semibold text-[#1E3A5F]">
                            {{ $d->parameter->name ?? '-' }}</td>
                        <td class="border border-slate-300 px-3 py-2 text-slate-600">
                            {{ $d->parameter->requirement ?? '-' }}</td>
                        <td class="border border-slate-300 px-3 py-2 font-mono font-bold text-[#1E3A5F]">
                            {{ $d->value }}</td>
                        <td
                            class="border border-slate-300 px-3 py-2 text-center font-bold {{ $d->condition_status === 'baik' ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $d->condition_status === 'baik' ? 'BAIK' : 'ABNORMAL' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Signatures (Teknisi, SPV, Manager) -->
    <div class="grid grid-cols-3 gap-4 text-center text-xs mt-12 pt-4">
        <!-- Teknisi -->
        <div class="border border-slate-200 rounded-lg p-3">
            <p class="text-slate-500 mb-12">Petugas Teknisi,</p>
            <p class="font-bold border-t border-slate-300 pt-1 text-[#1E3A5F]">
                {{ $logsheet->teknisi->name ?? '...........................' }}</p>
            <p class="text-[10px] text-slate-400">Teknisi Lapangan</p>
        </div>

        <!-- Supervisor -->
        <div class="border border-slate-200 rounded-lg p-3">
            <p class="text-slate-500 mb-12">Diverifikasi Oleh,</p>
            <p class="font-bold border-t border-slate-300 pt-1 text-[#1E3A5F]">
                {{ $logsheet->supervisor->name ?? '...........................' }}</p>
            <p class="text-[10px] text-slate-400">
                Supervisor
                {{ $logsheet->spv_approved_at ? '(' . $logsheet->spv_approved_at->format('d/m/Y') . ')' : '' }}
            </p>
        </div>

        <!-- Manager -->
        <div class="border border-slate-200 rounded-lg p-3">
            <p class="text-slate-500 mb-12">Disetujui Oleh,</p>
            <p class="font-bold border-t border-slate-300 pt-1 text-[#1E3A5F]">
                {{ $logsheet->manager->name ?? '...........................' }}</p>
            <p class="text-[10px] text-slate-400">
                Manager
                {{ $logsheet->manager_approved_at ? '(' . $logsheet->manager_approved_at->format('d/m/Y') . ')' : '' }}
            </p>
        </div>
    </div>

    <!-- Footer note -->
    <div class="mt-8 text-[10px] text-slate-400 border-t border-slate-200 pt-2 flex justify-between">
        <span>Dicetak secara elektronik dari Sistem Logsheet Monitoring Mesin</span>
        <span>{{ date('d/m/Y H:i') }}</span>
    </div>

</body>

</html>
