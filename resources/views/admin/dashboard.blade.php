@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan sistem peminjaman fasilitas')

@section('content')
<div class="space-y-6">
    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="stat-card accent-brand animate-fade-in-up" style="animation-delay:0ms">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Aset</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['total_assets'] }}</p>
            <p class="text-[11px] text-emerald-600 font-medium mt-0.5">{{ $stats['assets_tersedia'] }} tersedia</p>
        </div>
        <div class="stat-card accent-amber animate-fade-in-up" style="animation-delay:60ms">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hari Ini</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['peminjaman_hari_ini'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">peminjaman aktif</p>
        </div>
        <div class="stat-card accent-rose animate-fade-in-up" style="animation-delay:120ms">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $stats['pending'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">menunggu proses</p>
        </div>
        <div class="stat-card accent-emerald animate-fade-in-up" style="animation-delay:180ms">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Disetujui</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['approved'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">total approved</p>
        </div>
    </div>

    {{-- Two Column --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Today --}}
        <div class="card animate-fade-in-up" style="animation-delay:240ms">
            <div class="card-header">
                <h3 class="text-sm font-bold text-slate-800">📅 Jadwal Hari Ini</h3>
                <span class="badge-approved">{{ $todayPeminjamans->count() }}</span>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($todayPeminjamans as $p)
                <a href="{{ route('admin.peminjamans.show', $p) }}" class="block px-4 py-3 hover:bg-slate-50/80 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-700">{{ $p->asset->nama_aset }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $p->user->name }} · {{ \Carbon\Carbon::parse($p->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->jam_selesai)->format('H:i') }}</p>
                        </div>
                        <span class="badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span>
                    </div>
                </a>
                @empty
                <div class="px-4 py-8 text-center text-slate-400 text-xs">Tidak ada peminjaman hari ini</div>
                @endforelse
            </div>
        </div>

        {{-- Recent --}}
        <div class="card animate-fade-in-up" style="animation-delay:300ms">
            <div class="card-header">
                <h3 class="text-sm font-bold text-slate-800">🕐 Terbaru</h3>
                <a href="{{ route('admin.peminjamans.index') }}" class="text-brand-600 text-[11px] font-semibold hover:underline">Semua →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentPeminjamans as $p)
                <a href="{{ route('admin.peminjamans.show', $p) }}" class="block px-4 py-3 hover:bg-slate-50/80 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-700">{{ $p->asset->nama_aset }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $p->user->name }} · {{ $p->tgl_pakai->format('d M Y') }}</p>
                        </div>
                        <span class="badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span>
                    </div>
                </a>
                @empty
                <div class="px-4 py-8 text-center text-slate-400 text-xs">Belum ada data</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Bottom Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 animate-fade-in-up" style="animation-delay:360ms">
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 text-center">
            <p class="text-xl font-extrabold text-slate-800">{{ $stats['total_peminjaman'] }}</p>
            <p class="text-[10px] text-slate-400 font-medium mt-0.5 uppercase tracking-wider">Total Peminjaman</p>
        </div>
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 text-center">
            <p class="text-xl font-extrabold text-rose-600">{{ $stats['rejected'] }}</p>
            <p class="text-[10px] text-slate-400 font-medium mt-0.5 uppercase tracking-wider">Ditolak SAW</p>
        </div>
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 text-center">
            <p class="text-xl font-extrabold text-slate-500">{{ $stats['canceled'] }}</p>
            <p class="text-[10px] text-slate-400 font-medium mt-0.5 uppercase tracking-wider">Dibatalkan</p>
        </div>
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 text-center">
            <p class="text-xl font-extrabold text-brand-600">{{ $stats['total_users'] }}</p>
            <p class="text-[10px] text-slate-400 font-medium mt-0.5 uppercase tracking-wider">Total Pengguna</p>
        </div>
    </div>
</div>
@endsection
