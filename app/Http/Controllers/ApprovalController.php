<?php

namespace App\Http\Controllers;

use App\Models\ApprovalLog;
use App\Models\LogsheetHeader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    /**
     * Tampilkan antrian submission yang menunggu approval.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        $query = LogsheetHeader::with(['mesin.kategori', 'mesin.bangunan', 'teknisi']);

        switch ($tab) {
            case 'spv':
                $query->where('status', 'menunggu_spv');
                break;
            case 'manager':
                $query->where('status', 'menunggu_manager');
                break;
            case 'selesai':
                $query->where('status', 'selesai');
                break;
            case 'revisi':
                $query->where('status', 'perlu_revisi');
                break;
            case 'pending':
            default:
                $query->whereIn('status', ['menunggu_spv', 'menunggu_manager']);
                break;
        }

        $logsheets = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'pending' => LogsheetHeader::whereIn('status', ['menunggu_spv', 'menunggu_manager'])->count(),
            'spv' => LogsheetHeader::where('status', 'menunggu_spv')->count(),
            'manager' => LogsheetHeader::where('status', 'menunggu_manager')->count(),
            'selesai' => LogsheetHeader::where('status', 'selesai')->count(),
            'revisi' => LogsheetHeader::where('status', 'perlu_revisi')->count(),
        ];

        return view('approval.index', compact('logsheets', 'tab', 'counts'));
    }

    /**
     * Halaman review menyeluruh satu submission mesin.
     */
    public function review(LogsheetHeader $logsheet)
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

        return view('approval.review', compact('logsheet'));
    }

    /**
     * Eksekusi Approve satu keputusan per-submission.
     */
    public function approve(Request $request, LogsheetHeader $logsheet)
    {
        $actor = auth()->user() ?? User::role(User::ROLE_SUPERVISOR, User::ROLE_ADMIN)->first();
        $note = $request->input('note', 'Disetujui.');

        // ponytail: Guard role per tahap approval
        if ($logsheet->status === 'menunggu_spv' && !$actor->isSupervisor() && !$actor->isAdmin()) {
            return back()->with('error', 'Hanya Supervisor atau Admin yang berhak menyetujui tahap ini.');
        }
        if ($logsheet->status === 'menunggu_manager' && !$actor->isManager() && !$actor->isAdmin()) {
            return back()->with('error', 'Hanya Manager atau Admin yang berhak menyetujui tahap akhir ini.');
        }

        DB::beginTransaction();
        try {
            if ($logsheet->status === 'menunggu_spv') {
                $logsheet->update([
                    'status' => 'menunggu_manager',
                    'spv_id' => $actor->id,
                    'spv_approved_at' => now(),
                    'spv_note' => $note,
                ]);

                ApprovalLog::create([
                    'logsheet_header_id' => $logsheet->id,
                    'user_id' => $actor->id,
                    'role' => User::ROLE_SUPERVISOR,
                    'action' => 'approve',
                    'note' => $note,
                ]);

                $message = 'Submission berhasil disetujui oleh Supervisor dan diteruskan ke Manager.';
            } elseif ($logsheet->status === 'menunggu_manager') {
                $logsheet->update([
                    'status' => 'selesai',
                    'manager_id' => $actor->id,
                    'manager_approved_at' => now(),
                    'manager_note' => $note,
                ]);

                ApprovalLog::create([
                    'logsheet_header_id' => $logsheet->id,
                    'user_id' => $actor->id,
                    'role' => User::ROLE_MANAGER,
                    'action' => 'approve',
                    'note' => $note,
                ]);

                $message = 'Submission berhasil disetujui final oleh Manager (Status: Selesai).';
            } else {
                return back()->with('error', 'Status logsheet tidak valid untuk diapprove.');
            }

            DB::commit();
            return redirect()->route('approval.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses approval: ' . $e->getMessage());
        }
    }

    /**
     * Eksekusi Reject satu keputusan per-submission.
     */
    public function reject(Request $request, LogsheetHeader $logsheet)
    {
        $request->validate([
            'note' => 'required|string|min:3',
        ], [
            'note.required' => 'Alasan penolakan / catatan revisi wajib diisi.',
            'note.min' => 'Catatan revisi minimal 3 karakter.',
        ]);

        $actor = auth()->user() ?? User::role(User::ROLE_SUPERVISOR, User::ROLE_ADMIN)->first();
        $note = $request->input('note');

        // ponytail: Guard role per tahap reject
        if ($logsheet->status === 'menunggu_spv' && !$actor->isSupervisor() && !$actor->isAdmin()) {
            return back()->with('error', 'Hanya Supervisor atau Admin yang berhak menolak/merevisi tahap ini.');
        }
        if ($logsheet->status === 'menunggu_manager' && !$actor->isManager() && !$actor->isAdmin()) {
            return back()->with('error', 'Hanya Manager atau Admin yang berhak menolak tahap ini.');
        }

        DB::beginTransaction();
        try {
            if ($logsheet->status === 'menunggu_spv') {
                // SPV reject -> langsung kembali ke Teknisi (Perlu Revisi)
                $logsheet->update([
                    'status' => 'perlu_revisi',
                    'spv_id' => $actor->id,
                    'spv_note' => $note,
                ]);

                ApprovalLog::create([
                    'logsheet_header_id' => $logsheet->id,
                    'user_id' => $actor->id,
                    'role' => User::ROLE_SUPERVISOR,
                    'action' => 'reject',
                    'note' => $note,
                ]);

                $message = 'Submission ditolak oleh Supervisor. Dikembalikan ke Teknisi untuk direvisi.';
            } elseif ($logsheet->status === 'menunggu_manager') {
                // Sesuai PRD §4: Manager reject balik dulu ke SPV
                $logsheet->update([
                    'status' => 'menunggu_spv',
                    'manager_id' => $actor->id,
                    'manager_note' => $note,
                ]);

                ApprovalLog::create([
                    'logsheet_header_id' => $logsheet->id,
                    'user_id' => $actor->id,
                    'role' => User::ROLE_MANAGER,
                    'action' => 'reject',
                    'note' => $note,
                ]);

                $message = 'Submission ditolak oleh Manager dan dikembalikan ke antrian Supervisor untuk tindak lanjut.';
            }

            DB::commit();
            return redirect()->route('approval.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penolakan: ' . $e->getMessage());
        }
    }
}
