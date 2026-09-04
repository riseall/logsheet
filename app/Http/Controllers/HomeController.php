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

        $categories = Kategori::withCount('mesins')->get();

        return view('home', compact(
            'totalMachines',
            'pendingSpv',
            'pendingManager',
            'completedToday',
            'needAttentionToday',
            'recentLogsheets',
            'categories'
        ));
    }

    public function switchRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        session(['demo_user_id' => $request->user_id]);

        return back()->with('success', 'Berhasil beralih profil demo.');
    }
}
