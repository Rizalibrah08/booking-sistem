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

        // Calendar Events (All approved, pending, and rejected to see global availability and personal rejections)
        $allBookings = Peminjaman::with(['user', 'asset'])
            ->whereIn('status', ['approved', 'pending', 'rejected'])
            ->get();

        $calendarEvents = $allBookings->map(function ($booking) use ($userId) {
            $isMine = $booking->user_id === $userId;
            
            // Only show rejected tickets if it's the user's own ticket.
            // Don't show other people's rejected tickets on the calendar.
            if ($booking->status === 'rejected' && !$isMine) {
                return null;
            }

            $color = $isMine ? '#059669' : '#64748b'; // Emerald for mine, Slate for others
            if ($booking->status === 'pending') {
                $color = '#d97706'; // Amber for pending
            } elseif ($booking->status === 'rejected') {
                $color = '#e11d48'; // Rose/Red for rejected
            }
            
            return [
                'title' => $booking->asset->nama_aset . ($isMine ? ' (Anda)' : ' (' . $booking->user->name . ')'),
                'start' => $booking->tgl_pakai->format('Y-m-d') . 'T' . $booking->jam_mulai,
                'end'   => $booking->tgl_pakai->format('Y-m-d') . 'T' . $booking->jam_selesai,
                'color' => $color,
                'url'   => $isMine ? route('guru.peminjamans.show', $booking->id) : '#'
            ];
        })->filter()->values();

        return view('guru.dashboard', compact('stats', 'upcomingPeminjamans', 'recentPeminjamans', 'calendarEvents'));
    }
}
