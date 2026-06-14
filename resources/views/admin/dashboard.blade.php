@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page-title', 'Admin PT - Dashboard')
@section('page-subtitle', 'Selamat Datang, ' . Auth::user()->name)

@section('content')
<div class="space-y-8">
    {{-- HERO BANNER --}}
    <div class="relative w-full bg-brand-600 rounded-2xl overflow-hidden shadow-lg animate-fade-in-up">
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-brand-300 rounded-full mix-blend-overlay filter blur-3xl"></div>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between p-8 md:p-12 gap-8">
            <div class="text-white max-w-xl">
                <div class="inline-flex items-center gap-1.5 bg-white/20 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4 border border-white/10 backdrop-blur-md">
                    <div class="w-1.5 h-1.5 bg-brand-200 rounded-full animate-pulse"></div>
                    Sistem Aktif
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4 tracking-tight leading-tight">Sistem Manajemen<br>Peminjaman Fasilitas</h2>
                <p class="text-brand-100 text-sm leading-relaxed mb-8 opacity-90">
                    Panel kendali data peminjaman fasilitas dan inventaris di lingkungan MBS A.R. Fachruddin. Kelola aset, pantau jadwal, dan setujui tiket masuk secara terpusat.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('admin.peminjamans.create') }}" class="inline-flex items-center gap-2 bg-white text-brand-700 hover:bg-brand-50 px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Buat Tiket Baru
                    </a>
                </div>
                
                <div class="flex items-center gap-6 mt-8">
                    <div class="flex items-center gap-2 text-xs font-semibold text-brand-100">
                        <svg class="w-4 h-4 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Terpusat
                    </div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-brand-100">
                        <svg class="w-4 h-4 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Real-time
                    </div>
                </div>
            </div>
            
            <div class="hidden md:flex flex-shrink-0 w-48 h-48 bg-white/10 backdrop-blur-sm rounded-3xl items-center justify-center border border-white/20 p-6 transform hover:rotate-3 transition-transform duration-500 shadow-2xl">
                <img src="{{ asset('image/logo/Logo SMP Transparan.png') }}" alt="Logo Instansi" class="w-full h-full object-contain drop-shadow-lg filter brightness-110">
            </div>
        </div>
    </div>

    {{-- QUICK ACCESS MENU / CARDS --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Akses Cepat & Statistik</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
            <a href="{{ route('admin.assets.index') }}" class="quick-access-card bg-brand-600 group" style="animation-delay: 50ms">
                <div>
                    <div class="icon-wrapper">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-1 leading-tight">Data<br>Aset</h4>
                </div>
                <div class="flex items-center gap-2 mt-4 text-brand-100 text-xs font-medium group-hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Total {{ $stats['total_assets'] }} Aset
                </div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 text-brand-500 opacity-20 transform -rotate-12 group-hover:scale-110 transition-transform duration-500">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </a>

            <a href="{{ route('admin.peminjamans.index') }}" class="quick-access-card bg-amber-500 group" style="animation-delay: 100ms">
                <div>
                    <div class="icon-wrapper">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-1 leading-tight">Peminjaman<br>Hari Ini</h4>
                </div>
                <div class="flex items-center gap-2 mt-4 text-amber-100 text-xs font-medium group-hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    {{ $stats['peminjaman_hari_ini'] }} Aktif
                </div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 text-amber-600 opacity-20 transform rotate-12 group-hover:scale-110 transition-transform duration-500">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </a>
        </div>
    </div>

    {{-- Kalender Peminjaman --}}
    <div class="card animate-fade-in-up" style="animation-delay:150ms">
        <div class="card-header flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800">🗓️ Kalender Peminjaman</h3>
            <div class="flex gap-2">
                <span class="flex items-center gap-1 text-[10px] text-slate-500 font-semibold"><span class="w-2 h-2 rounded-full bg-emerald-600"></span> Disetujui</span>
                <span class="flex items-center gap-1 text-[10px] text-slate-500 font-semibold"><span class="w-2 h-2 rounded-full bg-amber-600"></span> Pending</span>
            </div>
        </div>
        <div class="card-body">
            <div id="calendar" class="w-full"></div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/id.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari'
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: {!! json_encode($calendarEvents) !!},
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false
            },
            height: 'auto',
            aspectRatio: 1.5,
            eventClick: function(info) {
                if(info.event.url) {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault(); // prevents browser from following link in current tab.
                }
            }
        });
        calendar.render();
    });
</script>
@endpush
