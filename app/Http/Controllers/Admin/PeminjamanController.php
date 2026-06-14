<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeminjamanRequest;
use App\Models\Asset;
use App\Models\Peminjaman;
use App\Models\User;
use App\Services\SawCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function __construct(
        protected SawCalculatorService $sawService
    ) {}

    /**
     * Daftar semua peminjaman (dengan filter).
     */
    public function index(Request $request): View
    {
        $query = Peminjaman::with(['user', 'asset', 'guarantor']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by tanggal
        if ($request->filled('tgl_pakai')) {
            $query->where('tgl_pakai', $request->input('tgl_pakai'));
        }

        // Filter by asset
        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->input('asset_id'));
        }

        $peminjamans = $query->latest()->paginate(15)->withQueryString();
        $assets = Asset::orderBy('nama_aset')->get();

        return view('admin.peminjamans.index', compact('peminjamans', 'assets'));
    }

    /**
     * Form buat peminjaman baru (termasuk untuk siswa).
     */
    public function create(): View
    {
        $assets = Asset::where('status', 'tersedia')->orderBy('nama_aset')->get();
        $gurus  = User::where('role', User::ROLE_GURU)->orderBy('name')->get();

        return view('admin.peminjamans.create', compact('assets', 'gurus'));
    }

    /**
     * Simpan peminjaman baru + trigger SAW jika ada konflik.
     */
    public function store(StorePeminjamanRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Set user_id ke user yang sedang login
        $data['user_id'] = Auth::id();

        // Hitung lead time score
        $data['lead_time_score'] = SawCalculatorService::calculateLeadTimeScore($data['tgl_pakai']);

        // Handle boolean
        $data['is_student_borrower'] = $request->boolean('is_student_borrower');

        // Jika bukan peminjaman siswa, hapus field siswa
        if (! $data['is_student_borrower']) {
            $data['nama_siswa']    = null;
            $data['guarantor_id']  = null;
        }

        // ── Hak Veto: Jika user adalah Kepsek atau Admin ───────────
        $user = Auth::user();
        if (($user->isKepsek() || $user->isAdmin()) && ! $data['is_student_borrower']) {
            $data['status'] = Peminjaman::STATUS_APPROVED;

            $peminjaman = Peminjaman::create($data);

            // Force cancel semua konflik yang ada
            $conflicts = Peminjaman::conflictsWith(
                $data['asset_id'],
                $data['tgl_pakai'],
                $data['jam_mulai'],
                $data['jam_selesai']
            )
            ->where('id', '!=', $peminjaman->id)
            ->get();

            foreach ($conflicts as $conflict) {
                $conflict->update([
                    'status'        => 'rejected',
                    'cancel_reason' => 'Otomatis ditolak: Hak Veto Admin / Kepala Sekolah.',
                ]);
            }

            return redirect()
                ->route('admin.peminjamans.show', $peminjaman)
                ->with('success', 'Peminjaman langsung disetujui (Hak Veto Admin/Kepsek).');
        }

        // ── Simpan peminjaman sebagai pending ────────────────────────
        $peminjaman = Peminjaman::create($data);

        // ── Cek konflik dan jalankan SAW jika perlu ──────────────────
        $conflictCount = Peminjaman::conflictsWith(
            $data['asset_id'],
            $data['tgl_pakai'],
            $data['jam_mulai'],
            $data['jam_selesai']
        )->count();

        if ($conflictCount > 1) {
            // Ada konflik — jalankan SAW
            $this->sawService->resolveConflicts(
                $data['asset_id'],
                $data['tgl_pakai'],
                $data['jam_mulai'],
                $data['jam_selesai']
            );

            // Refresh data
            $peminjaman->refresh();

            if ($peminjaman->status === 'rejected') {
                if (str_contains($peminjaman->cancel_reason, 'Hak Veto')) {
                    return redirect()
                        ->route('admin.peminjamans.show', $peminjaman)
                        ->with('error', 'Peminjaman untuk siswa otomatis ditolak karena jadwal bentrok dengan agenda Kepala Sekolah/Admin (Hak Veto).');
                }
                return redirect()
                    ->route('admin.peminjamans.show', $peminjaman)
                    ->with('error', "Terdeteksi {$conflictCount} konflik jadwal. Peminjaman siswa ditolak karena kalah prioritas (SAW). Skor: {$peminjaman->saw_final_score}");
            }

            return redirect()
                ->route('admin.peminjamans.show', $peminjaman)
                ->with('info', "Terdeteksi {$conflictCount} konflik jadwal. Peminjaman siswa disetujui karena menang prioritas (SAW). Skor: {$peminjaman->saw_final_score}");
        }

        // Tidak ada konflik — auto approve
        $peminjaman->update(['status' => Peminjaman::STATUS_APPROVED]);

        return redirect()
            ->route('admin.peminjamans.show', $peminjaman)
            ->with('success', 'Peminjaman berhasil diajukan dan disetujui (tidak ada konflik).');
    }

    /**
     * Detail peminjaman + hasil SAW.
     */
    public function show(Peminjaman $peminjaman): View
    {
        $peminjaman->load(['user', 'asset', 'guarantor']);

        // Cari peminjaman lain yang konflik untuk menampilkan perbandingan SAW
        $conflicts = Peminjaman::conflictsWith(
            $peminjaman->asset_id,
            $peminjaman->tgl_pakai->toDateString(),
            $peminjaman->jam_mulai,
            $peminjaman->jam_selesai
        )
        ->with(['user', 'guarantor'])
        ->where('id', '!=', $peminjaman->id)
        ->get();

        return view('admin.peminjamans.show', compact('peminjaman', 'conflicts'));
    }

    /**
     * Force cancel peminjaman yang sudah approved.
     */
    public function cancel(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $request->validate([
            'cancel_reason' => ['required', 'string', 'max:500'],
        ], [
            'cancel_reason.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $peminjaman->update([
            'status'        => Peminjaman::STATUS_CANCELED,
            'cancel_reason' => $request->input('cancel_reason'),
        ]);

        return redirect()
            ->route('admin.peminjamans.index')
            ->with('success', 'Peminjaman berhasil dibatalkan.');
    }
}
