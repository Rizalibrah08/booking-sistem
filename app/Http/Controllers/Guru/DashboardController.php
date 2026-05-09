<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard guru/staf — overview peminjaman pribadi.
     */
    public function index(): View
    {
        $userId = Auth::id();
        $today  = now()->toDateString();

        $stats = [
            'total_peminjaman' => Peminjaman::where('user_id', $userId)->count(),
            'pending'          => Peminjaman::where('user_id', $userId)->where('status', 'pending')->count(),
            'approved'         => Peminjaman::where('user_id', $userId)->where('status', 'approved')->count(),
            'rejected'         => Peminjaman::where('user_id', $userId)->where('status', 'rejected')->count(),
            'hari_ini'         => Peminjaman::where('user_id', $userId)->where('tgl_pakai', $today)->count(),
        ];

        $upcomingPeminjamans = Peminjaman::with('asset')
            ->where('user_id', $userId)
            ->where('tgl_pakai', '>=', $today)
            ->where('status', 'approved')
            ->orderBy('tgl_pakai')
            ->orderBy('jam_mulai')
            ->take(5)
            ->get();

        $recentPeminjamans = Peminjaman::with('asset')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('guru.dashboard', compact('stats', 'upcomingPeminjamans', 'recentPeminjamans'));
    }
}
