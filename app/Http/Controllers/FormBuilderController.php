<?php

namespace App\Http\Controllers;

use App\Models\FormParameter;
use App\Models\FormTemplate;
use App\Models\Mesin;
use App\Models\User;
use Illuminate\Http\Request;

class FormBuilderController extends Controller
{
    /**
     * Daftar mesin dan status template parameter.
     */
    public function index()
    {
        $machines = Mesin::with(['kategori', 'bangunan', 'latestTemplate.parameters'])
            ->where('status_aktif', true)
            ->get();

        return view('form_builder.index', compact('machines'));
    }

    /**
     * Form builder dinamis per mesin (Kelola Parameter & Syarat).
     */
    public function edit(Mesin $machine)
    {
        $machine->load(['kategori', 'bangunan']);
        $template = $machine->latestTemplate()->with('parameters')->first();

        if (!$template) {
            $admin = User::where('role', 'admin')->first() ?? User::first();
            $template = FormTemplate::create([
                'machine_id' => $machine->id,
                'version' => 1,
                'created_by' => $admin ? $admin->id : 1,
            ]);
            $template->load('parameters');
        }

        return view('form_builder.edit', compact('machine', 'template'));
    }

    /**
     * Tambah parameter baru ke template mesin.
     */
    public function storeParameter(Request $request, Mesin $machine)
    {
        $request->validate([
            'template_id' => 'required|exists:form_templates,id',
            'name' => 'required|string|max:255',
            'requirement' => 'nullable|string|max:255',
        ]);

        $maxOrder = FormParameter::where('form_template_id', $request->template_id)->max('sort_order') ?? 0;

        FormParameter::create([
            'form_template_id' => $request->template_id,
            'name' => $request->name,
            'requirement' => $request->requirement,
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Parameter "' . $request->name . '" berhasil ditambahkan.');
    }

    /**
     * Update data parameter.
     */
    public function updateParameter(Request $request, FormParameter $parameter)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'requirement' => 'nullable|string|max:255',
        ]);

        $parameter->update([
            'name' => $request->name,
            'requirement' => $request->requirement,
        ]);

        return back()->with('success', 'Parameter berhasil diperbarui.');
    }

    /**
     * Hapus parameter dari template.
     */
    public function destroyParameter(FormParameter $parameter)
    {
        $name = $parameter->name;
        $parameter->delete();

        return back()->with('success', 'Parameter "' . $name . '" berhasil dihapus.');
    }
}
