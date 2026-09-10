<?php

namespace App\Http\Controllers;

use App\Models\Bangunan;
use App\Models\Kategori;
use App\Models\LogsheetDetail;
use App\Models\LogsheetHeader;
use App\Models\Mesin;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalMachines = Mesin::where('status_aktif', true)->count();
        $pendingSpv = LogsheetHeader::where('status', 'menunggu_spv')->count();
        $pendingManager = LogsheetHeader::where('status', 'menunggu_manager')->count();
        $completedToday = LogsheetHeader::where('status', 'selesai')
            ->whereDate('date', today())
            ->count();

        $needAttentionToday = LogsheetDetail::where('condition_status', 'perlu_perhatian')
            ->whereHas('header', function ($q) {
                $q->whereDate('date', today());
            })
            ->count();

        $recentLogsheets = LogsheetHeader::with(['mesin.kategori', 'mesin.bangunan', 'teknisi'])
            ->latest()
            ->take(6)
            ->get();

        $categories = Kategori::withCount(['mesins' => function ($query) {
            $query->where('status_aktif', 1);
        }])->get();

        $user = auth()->user();
        // ponytail: Metrik adaptif per role tanpa query ganda.
        $myPendingRevision = $user && $user->isTeknisi()
            ? LogsheetHeader::where('teknisi_id', $user->id)->where('status', 'perlu_revisi')->count()
            : LogsheetHeader::where('status', 'perlu_revisi')->count();

        $mySubmittedToday = $user && $user->isTeknisi()
            ? LogsheetHeader::where('teknisi_id', $user->id)->whereDate('date', today())->count()
            : LogsheetHeader::whereDate('date', today())->count();

        return view('home', compact(
            'totalMachines',
            'pendingSpv',
            'pendingManager',
            'completedToday',
            'needAttentionToday',
            'recentLogsheets',
            'categories',
            'myPendingRevision',
            'mySubmittedToday'
        ));
    }
}
