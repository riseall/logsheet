<?php

namespace App\Http\Controllers;

use App\Models\Bangunan;
use App\Models\FormTemplate;
use App\Models\Kategori;
use App\Models\LogsheetDetail;
use App\Models\LogsheetHeader;
use App\Models\Mesin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsheetController extends Controller
{
    /**
     * Step 1: Tampilkan kartu kategori (HVAC, ME, CU, dll).
     */
    public function index()
    {
        $categories = Kategori::withCount(['mesins' => function ($q) {
            $q->where('status_aktif', true);
        }])->get();

        return view('logsheet.index', compact('categories'));
    }

    /**
     * Step 2: Tampilkan bangunan yang memiliki mesin di kategori terpilih.
     */
    public function buildings(Kategori $category)
    {
        $query = Bangunan::withCount(['mesins' => function ($q) use ($category) {
            $q->where('category_id', $category->id)
              ->where('status_aktif', true);
        }]);

        $user = auth()->user();
        if (!($user && ($user->isSupervisor() || $user->isAdmin()))) {
            $query->whereHas('mesins', function ($q) use ($category) {
                $q->where('category_id', $category->id)
                  ->where('status_aktif', true);
            });
        }

        $buildings = $query->get();

        return view('logsheet.buildings', compact('category', 'buildings'));
    }

    /**
     * Tambah Bangunan Baru (Hanya Admin / SPV)
     */
    public function storeBuilding(Request $request)
    {
        $user = auth()->user();
        if (!($user && ($user->isSupervisor() || $user->isAdmin()))) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $nextId = Bangunan::max('id') + 1;
        $code = 'BLD-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Bangunan::create([
            'name' => $request->name,
            'code' => $code,
            'location' => $request->location,
        ]);

        return back()->with('success', 'Bangunan/Gedung baru berhasil ditambahkan.');
    }

    /**
     * Edit Bangunan (Hanya Admin / SPV)
     */
    public function updateBuilding(Request $request, Bangunan $building)
    {
        $user = auth()->user();
        if (!($user && ($user->isSupervisor() || $user->isAdmin()))) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $building->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return back()->with('success', 'Data gedung berhasil diperbarui.');
    }

    /**
     * Hapus Bangunan (Hanya Admin / SPV)
     */
    public function destroyBuilding(Bangunan $building)
    {
        $user = auth()->user();
        if (!($user && ($user->isSupervisor() || $user->isAdmin()))) {
            abort(403, 'Unauthorized');
        }

        $mesinCount = $building->mesins()->count();
        if ($mesinCount > 0) {
            return back()->with('error', "Gagal dihapus! Gedung ini masih terhubung dengan {$mesinCount} unit mesin (bisa jadi dari kategori lain atau mesin non-aktif).");
        }

        $building->delete();
        return back()->with('success', 'Gedung berhasil dihapus.');
    }

    /**
     * Step 3: Tampilkan mesin dalam kategori & bangunan terpilih.
     */
    public function machines(Kategori $category, Bangunan $building, Request $request)
    {
        $machines = Mesin::where('category_id', $category->id)
            ->where('building_id', $building->id)
            ->where('status_aktif', true)
            ->with(['latestTemplate.parameters', 'logsheets' => function ($q) {
                $q->latest()->take(1);
            }])
            ->get();

        // Pastikan setiap mesin memiliki template aktif v1 jika belum ada
        foreach ($machines as $m) {
            if (!$m->latestTemplate) {
                $admin = auth()->user() ?? User::first();
                $tmpl = FormTemplate::create([
                    'machine_id' => $m->id,
                    'version' => 1,
                    'created_by' => $admin ? $admin->id : 1,
                ]);
                $m->load('latestTemplate.parameters');
            }
        }

        $selectedMachineId = $request->query('machine_id', $machines->first()->id ?? null);

        $pmmtMachines = DB::connection('db_pmmt')->table('mesins')->select('id', 'nama_mesin', 'tipe_mesin', 'no_asset')->get();

        return view('logsheet.machines', compact('category', 'building', 'machines', 'selectedMachineId', 'pmmtMachines'));
    }

    /**
     * Step 4: Tampilkan form pengisian logsheet dinamis per mesin.
     */
    public function create(Mesin $machine)
    {
        $machine->load(['kategori', 'bangunan']);
        $template = $machine->latestTemplate()->with('parameters')->first();

        // Jika mesin belum memiliki template form, buat versi default 1
        if (!$template) {
            $admin = auth()->user() ?? User::first();
            $template = FormTemplate::create([
                'machine_id' => $machine->id,
                'version' => 1,
                'created_by' => $admin ? $admin->id : 1,
            ]);
        }

        return view('logsheet.create', compact('machine', 'template'));
    }

    /**
     * Simpan pengisian logsheet (Draft atau Submit ke SPV).
     */
    public function store(Request $request, Mesin $machine)
    {
        $request->validate([
            'form_template_id' => 'required|exists:form_templates,id',
            'date' => 'required|date',
            'shift' => 'required|string',
            'action' => 'required|in:draft,submit',
            'params' => 'required|array',
            'params.*.value' => 'nullable|string',
            'params.*.condition_status' => 'required|in:baik,perlu_perhatian',
        ]);

        $status = $request->action === 'submit' ? 'menunggu_spv' : 'draft';
        $user = auth()->user() ?? User::first();

        DB::beginTransaction();
        try {
            $header = LogsheetHeader::create([
                'machine_id' => $machine->id,
                'form_template_id' => $request->form_template_id,
                'teknisi_id' => $user->id,
                'date' => $request->date,
                'shift' => $request->shift,
                'status' => $status,
            ]);

            foreach ($request->params as $paramId => $data) {
                LogsheetDetail::create([
                    'logsheet_header_id' => $header->id,
                    'form_parameter_id' => $paramId,
                    'value' => $data['value'] ?? '-',
                    'condition_status' => $data['condition_status'] ?? 'baik',
                ]);
            }

            DB::commit();

            $msg = $status === 'menunggu_spv' 
                ? 'Logsheet berhasil disubmit! Menunggu persetujuan Supervisor.' 
                : 'Logsheet berhasil disimpan sebagai Draft.';

            return redirect()->route('logsheet.show', $header->id)->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan logsheet: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail hasil pengisian logsheet & riwayat approval.
     */
    public function show(LogsheetHeader $logsheet)
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

        return view('logsheet.show', compact('logsheet'));
    }
}
