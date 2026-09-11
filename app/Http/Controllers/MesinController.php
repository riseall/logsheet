<?php

namespace App\Http\Controllers;

use App\Models\Bangunan;
use App\Models\Kategori;
use App\Models\Mesin;
use Illuminate\Http\Request;

class MesinController extends Controller
{
    /**
     * Tambah Mesin Baru (Hanya Admin / SPV)
     */
    public function store(Request $request, Kategori $category, Bangunan $building)
    {
        $user = auth()->user();
        if (!($user && ($user->isSupervisor() || $user->isAdmin()))) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'asset_number' => 'nullable|string|max:255',
        ]);

        $nextId = Mesin::max('id') + 1;
        $code = 'MSN-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Mesin::create([
            'name' => $request->name,
            'code' => $code,
            'type' => $request->type,
            'asset_number' => $request->asset_number,
            'room' => null,
            'category_id' => $category->id,
            'building_id' => $building->id,
            'status_aktif' => true,
        ]);

        return back()->with('success', 'Unit mesin baru berhasil ditambahkan.');
    }

    /**
     * Edit Mesin (Hanya Admin / SPV)
     */
    public function update(Request $request, Mesin $machine)
    {
        $user = auth()->user();
        if (!($user && ($user->isSupervisor() || $user->isAdmin()))) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'asset_number' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
        ]);

        $machine->update([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'asset_number' => $request->asset_number,
            'room' => $request->room,
        ]);

        return back()->with('success', 'Data mesin berhasil diperbarui.');
    }

    /**
     * Hapus / Non-aktifkan Mesin (Hanya Admin / SPV)
     */
    public function destroy(Mesin $machine)
    {
        $user = auth()->user();
        if (!($user && ($user->isSupervisor() || $user->isAdmin()))) {
            abort(403, 'Unauthorized');
        }

        // Soft delete dengan menonaktifkan status
        $machine->update(['status_aktif' => false]);
        
        return back()->with('success', 'Mesin berhasil dihapus (dinonaktifkan).');
    }
}
