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
        $buildings = Bangunan::whereHas('mesins', function ($q) use ($category) {
            $q->where('category_id', $category->id)
              ->where('status_aktif', true);
        })->withCount(['mesins' => function ($q) use ($category) {
            $q->where('category_id', $category->id)
              ->where('status_aktif', true);
        }])->get();

        return view('logsheet.buildings', compact('category', 'buildings'));
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
                $admin = User::where('role', 'admin')->first() ?? User::first();
                $tmpl = FormTemplate::create([
                    'machine_id' => $m->id,
                    'version' => 1,
                    'created_by' => $admin ? $admin->id : 1,
                ]);
                $m->load('latestTemplate.parameters');
            }
        }

        $selectedMachineId = $request->query('machine_id', $machines->first()->id ?? null);

        return view('logsheet.machines', compact('category', 'building', 'machines', 'selectedMachineId'));
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
            $admin = User::where('role', 'admin')->first() ?? User::first();
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
        $user = auth()->user() ?? User::where('role', 'teknisi')->first() ?? User::first();

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
