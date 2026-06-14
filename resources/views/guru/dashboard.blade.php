@extends('layouts.guru')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . Auth::user()->name)

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
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4 tracking-tight leading-tight">Portal Pengajuan<br>Peminjaman Fasilitas</h2>
                <p class="text-brand-100 text-sm leading-relaxed mb-8 opacity-90">
                    Selamat datang di portal peminjaman fasilitas MBS A.R. Fachruddin. Anda dapat mengajukan peminjaman aset, memantau status persetujuan, dan melihat riwayat tiket Anda.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('guru.peminjamans.create') }}" class="inline-flex items-center gap-2 bg-white text-brand-700 hover:bg-brand-50 px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Ajukan Peminjaman
                    </a>
                </div>
            </div>
            
            <div class="hidden md:flex flex-shrink-0 w-48 h-48 bg-white/10 backdrop-blur-sm rounded-3xl items-center justify-center border border-white/20 p-6 transform rotate-3 hover:rotate-0 transition-transform duration-500 shadow-2xl">
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
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <a href="{{ route('guru.peminjamans.index') }}" class="quick-access-card bg-brand-600 group" style="animation-delay: 50ms">
                <div>
                    <div class="icon-wrapper">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-1 leading-tight">Total<br>Tiket</h4>
                </div>
                <div class="flex items-center gap-2 mt-4 text-brand-100 text-xs font-medium group-hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Semua {{ $stats['total_peminjaman'] }} Peminjaman
                </div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 text-brand-500 opacity-20 transform -rotate-12 group-hover:scale-110 transition-transform duration-500">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </a>

            <div class="quick-access-card bg-amber-500 group" style="animation-delay: 100ms">
                <div>
                    <div class="icon-wrapper">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-1 leading-tight">Status<br>Pending</h4>
                </div>
                <div class="flex items-center gap-2 mt-4 text-amber-100 text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    {{ $stats['pending'] }} Menunggu Diproses
                </div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 text-amber-600 opacity-20 transform rotate-12 group-hover:scale-110 transition-transform duration-500">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="quick-access-card bg-emerald-600 group" style="animation-delay: 150ms">
                <div>
                    <div class="icon-wrapper">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-1 leading-tight">Tiket<br>Disetujui</h4>
                </div>
                <div class="flex items-center gap-2 mt-4 text-emerald-100 text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ $stats['approved'] }} Tiket Diterima
                </div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 text-emerald-700 opacity-20 transform -rotate-6 group-hover:scale-110 transition-transform duration-500">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="quick-access-card bg-slate-700 group" style="animation-delay: 200ms">
                <div>
                    <div class="icon-wrapper">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-1 leading-tight">Jadwal<br>Hari Ini</h4>
                </div>
                <div class="flex items-center gap-2 mt-4 text-slate-300 text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $stats['hari_ini'] }} Booking Aktif
                </div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 text-slate-800 opacity-30 transform rotate-6 group-hover:scale-110 transition-transform duration-500">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Kalender Peminjaman --}}
    <div class="card animate-fade-in-up" style="animation-delay:250ms">
        <div class="card-header flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800">🗓️ Kalender Ketersediaan Fasilitas</h3>
            <div class="flex gap-2">
                <span class="flex items-center gap-1 text-[10px] text-slate-500 font-semibold"><span class="w-2 h-2 rounded-full bg-emerald-600"></span> Jadwal Anda</span>
                <span class="flex items-center gap-1 text-[10px] text-slate-500 font-semibold"><span class="w-2 h-2 rounded-full bg-slate-500"></span> Jadwal Orang Lain</span>
                <span class="flex items-center gap-1 text-[10px] text-slate-500 font-semibold"><span class="w-2 h-2 rounded-full bg-amber-600"></span> Pending</span>
            </div>
        </div>
        <div class="card-body">
            <div id="calendar" class="w-full"></div>
        </div>
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
                if(info.event.url && info.event.url !== '#') {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault(); // prevents browser from following link in current tab.
                } else {
                    info.jsEvent.preventDefault(); // If it's someone else's booking and URL is #
                }
            }
        });
        calendar.render();
    });
</script>
@endpush
