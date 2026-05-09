<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard admin — overview statistik sistem.
     */
    public function index(): View
    {
        $today = now()->toDateString();

        $stats = [
            'total_assets'         => Asset::count(),
            'assets_tersedia'      => Asset::where('status', 'tersedia')->count(),
            'total_peminjaman'     => Peminjaman::count(),
            'peminjaman_hari_ini'  => Peminjaman::where('tgl_pakai', $today)->count(),
            'pending'              => Peminjaman::where('status', 'pending')->count(),
            'approved'             => Peminjaman::where('status', 'approved')->count(),
            'rejected'             => Peminjaman::where('status', 'rejected')->count(),
            'canceled'             => Peminjaman::where('status', 'canceled')->count(),
            'total_users'          => User::count(),
        ];

        // 10 peminjaman terbaru
        $recentPeminjamans = Peminjaman::with(['user', 'asset'])
            ->latest()
            ->take(10)
            ->get();

        // Peminjaman hari ini
        $todayPeminjamans = Peminjaman::with(['user', 'asset'])
            ->where('tgl_pakai', $today)
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPeminjamans', 'todayPeminjamans'));
    }
}
