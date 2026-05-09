@extends('layouts.guru')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . Auth::user()->name)

@section('content')
<div class="space-y-6">
    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="stat-card accent-brand animate-fade-in-up" style="animation-delay:0ms">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['total_peminjaman'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">peminjaman</p>
        </div>
        <div class="stat-card accent-amber animate-fade-in-up" style="animation-delay:60ms">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $stats['pending'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">menunggu</p>
        </div>
        <div class="stat-card accent-emerald animate-fade-in-up" style="animation-delay:120ms">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Disetujui</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['approved'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">approved</p>
        </div>
        <div class="stat-card accent-rose animate-fade-in-up" style="animation-delay:180ms">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hari Ini</p>
            <p class="text-2xl font-extrabold text-brand-600 mt-1">{{ $stats['hari_ini'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">booking</p>
        </div>
    </div>

    {{-- CTA --}}
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl p-4 flex items-center justify-between animate-fade-in-up" style="animation-delay:240ms">
        <div>
            <p class="text-white font-bold text-sm">Butuh fasilitas?</p>
            <p class="text-emerald-100 text-[11px] mt-0.5">Ajukan peminjaman sekarang</p>
        </div>
        <a href="{{ route('guru.peminjamans.create') }}" class="bg-white text-emerald-700 text-xs font-bold px-4 py-2 rounded-lg hover:bg-emerald-50 transition-all shadow">
            + Ajukan Peminjaman
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Upcoming --}}
        <div class="card animate-fade-in-up" style="animation-delay:300ms">
            <div class="card-header">
                <h3 class="text-sm font-bold text-slate-800">📅 Jadwal Mendatang</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($upcomingPeminjamans as $p)
                <a href="{{ route('guru.peminjamans.show', $p) }}" class="block px-4 py-3 hover:bg-slate-50/80 transition-colors">
                    <p class="text-xs font-bold text-slate-700">{{ $p->asset->nama_aset }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $p->tgl_pakai->format('d M Y') }} · {{ \Carbon\Carbon::parse($p->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->jam_selesai)->format('H:i') }}</p>
                </a>
                @empty
                <div class="px-4 py-8 text-center text-slate-400 text-xs">Tidak ada jadwal mendatang</div>
                @endforelse
            </div>
        </div>

        {{-- Recent --}}
        <div class="card animate-fade-in-up" style="animation-delay:360ms">
            <div class="card-header">
                <h3 class="text-sm font-bold text-slate-800">🕐 Terbaru</h3>
                <a href="{{ route('guru.peminjamans.index') }}" class="text-emerald-600 text-[11px] font-semibold hover:underline">Semua →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentPeminjamans as $p)
                <a href="{{ route('guru.peminjamans.show', $p) }}" class="block px-4 py-3 hover:bg-slate-50/80 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-700">{{ $p->asset->nama_aset }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $p->tgl_pakai->format('d M Y') }}</p>
                        </div>
                        <span class="badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span>
                    </div>
                </a>
                @empty
                <div class="px-4 py-8 text-center text-slate-400 text-xs">Belum ada peminjaman</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
