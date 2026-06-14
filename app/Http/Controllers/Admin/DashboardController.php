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

        // Calendar Events (All approved and pending)
        $allBookings = Peminjaman::with(['user', 'asset'])
            ->whereIn('status', ['approved', 'pending'])
            ->get();

        $calendarEvents = $allBookings->map(function ($booking) {
            $color = $booking->status === 'approved' ? '#059669' : '#d97706'; // emerald-600 or amber-600
            
            return [
                'title' => $booking->asset->nama_aset . ' (' . $booking->user->name . ')',
                'start' => $booking->tgl_pakai->format('Y-m-d') . 'T' . $booking->jam_mulai,
                'end'   => $booking->tgl_pakai->format('Y-m-d') . 'T' . $booking->jam_selesai,
                'color' => $color,
                'url'   => route('admin.peminjamans.show', $booking->id)
            ];
        });

        return view('admin.dashboard', compact('stats', 'recentPeminjamans', 'todayPeminjamans', 'calendarEvents'));
    }
}
