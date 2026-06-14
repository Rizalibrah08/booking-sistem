<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeminjamanRequest;
use App\Models\Asset;
use App\Models\Peminjaman;
use App\Services\SawCalculatorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function __construct(
        protected SawCalculatorService $sawService
    ) {}

    /**
     * Daftar peminjaman milik user yang login.
     */
    public function index(): View
    {
        $peminjamans = Peminjaman::with('asset')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('guru.peminjamans.index', compact('peminjamans'));
    }

    /**
     * Form ajukan peminjaman.
     */
    public function create(): View
    {
        $assets = Asset::where('status', 'tersedia')->orderBy('nama_aset')->get();

        return view('guru.peminjamans.create', compact('assets'));
    }

    /**
     * Simpan peminjaman guru/staf + trigger SAW jika konflik.
     */
    public function store(StorePeminjamanRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['user_id']            = Auth::id();
        $data['lead_time_score']    = SawCalculatorService::calculateLeadTimeScore($data['tgl_pakai']);
        $data['is_student_borrower'] = false;
        $data['nama_siswa']         = null;
        $data['guarantor_id']       = null;

        // Simpan sebagai pending
        $peminjaman = Peminjaman::create($data);

        // Cek konflik
        $conflictCount = Peminjaman::conflictsWith(
            $data['asset_id'],
            $data['tgl_pakai'],
            $data['jam_mulai'],
            $data['jam_selesai']
        )->count();

        if ($conflictCount > 1) {
            $this->sawService->resolveConflicts(
                $data['asset_id'],
                $data['tgl_pakai'],
                $data['jam_mulai'],
                $data['jam_selesai']
            );

            $peminjaman->refresh();

            if ($peminjaman->status === Peminjaman::STATUS_REJECTED) {
                if (str_contains($peminjaman->cancel_reason, 'Hak Veto')) {
                    return redirect()
                        ->route('guru.peminjamans.show', $peminjaman)
                        ->with('error', 'Peminjaman otomatis ditolak karena jadwal bentrok dengan agenda Kepala Sekolah/Admin (Hak Veto).');
                }
                return redirect()
                    ->route('guru.peminjamans.show', $peminjaman)
                    ->with('error', "Terdeteksi konflik jadwal. Peminjaman Anda ditolak karena kalah prioritas (SAW). Skor Anda: {$peminjaman->saw_final_score}");
            }

            return redirect()
                ->route('guru.peminjamans.show', $peminjaman)
                ->with('info', "Terdeteksi konflik jadwal. Peminjaman Anda disetujui karena menang prioritas (SAW). Skor Anda: {$peminjaman->saw_final_score}");
        }

        // Tidak ada konflik
        $peminjaman->update(['status' => Peminjaman::STATUS_APPROVED]);

        return redirect()
            ->route('guru.peminjamans.show', $peminjaman)
            ->with('success', 'Peminjaman berhasil diajukan dan disetujui.');
    }

    /**
     * Detail peminjaman.
     */
    public function show(Peminjaman $peminjaman): View
    {
        // Pastikan hanya bisa lihat milik sendiri
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke peminjaman ini.');
        }

        $peminjaman->load(['asset', 'guarantor']);

        return view('guru.peminjamans.show', compact('peminjaman'));
    }

    /**
     * Batalkan peminjaman milik sendiri.
     */
    public function cancel(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        // Pastikan hanya bisa cancel milik sendiri
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke peminjaman ini.');
        }

        if (!in_array($peminjaman->status, [Peminjaman::STATUS_PENDING, Peminjaman::STATUS_APPROVED])) {
            return back()->with('error', 'Status peminjaman saat ini tidak dapat dibatalkan.');
        }

        $request->validate([
            'cancel_reason' => ['required', 'string', 'max:500'],
        ], [
            'cancel_reason.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $peminjaman->update([
            'status'        => Peminjaman::STATUS_CANCELED,
            'cancel_reason' => 'Dibatalkan oleh peminjam: ' . $request->input('cancel_reason'),
        ]);

        return redirect()
            ->route('guru.peminjamans.show', $peminjaman)
            ->with('success', 'Peminjaman berhasil dibatalkan.');
    }
}
