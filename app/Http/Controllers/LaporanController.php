<?php

namespace App\Http\Controllers;

use App\Models\Bangunan;
use App\Models\Kategori;
use App\Models\LogsheetHeader;
use App\Models\Mesin;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Rekap Histori Logsheet dengan multi-filter.
     */
    public function index(Request $request)
    {
        $categories = Kategori::all();
        $buildings = Bangunan::all();
        $machines = Mesin::where('status_aktif', true)->get();

        $query = LogsheetHeader::with([
            'mesin.kategori',
            'mesin.bangunan',
            'teknisi',
            'supervisor',
            'manager',
            'details',
        ]);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('mesin', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('building_id')) {
            $query->whereHas('mesin', function ($q) use ($request) {
                $q->where('building_id', $request->building_id);
            });
        }

        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        $logsheets = $query->latest('date')->paginate(15)->withQueryString();

        return view('laporan.index', compact('logsheets', 'categories', 'buildings', 'machines'));
    }

    /**
     * Cetak dokumen logsheet siap print (format resmi perusahaan).
     */
    public function print(LogsheetHeader $logsheet)
    {
        $logsheet->load([
            'mesin.kategori',
            'mesin.bangunan',
            'template',
            'teknisi',
            'supervisor',
            'manager',
            'details.parameter',
            'approvalLogs.user',
        ]);

        return view('laporan.print', compact('logsheet'));
    }
}
