<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SPK Peminjaman MBS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50/80">
    <div class="flex min-h-screen">

        {{-- ═══ SIDEBAR ═══ --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-60 bg-white border-r border-slate-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
            {{-- Logo --}}
            <div class="px-5 py-5 flex items-center gap-3 border-b border-slate-100">
                <img src="{{ asset('image/logo/Logo SMP Transparan.png') }}" alt="Logo" class="w-9 h-9 object-contain drop-shadow-sm">
                <div class="min-w-0">
                    <h1 class="text-brand-900 font-extrabold text-sm leading-tight truncate">MBS Fachruddin</h1>
                    <p class="text-brand-600 text-[10px] font-bold tracking-wide uppercase">Panel Admin</p>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 pb-4 overflow-y-auto">
                <p class="sidebar-section">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                    Dashboard
                </a>

                <p class="sidebar-section">Kelola Data</p>
                <a href="{{ route('admin.assets.index') }}" class="sidebar-link {{ request()->routeIs('admin.assets.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Data Aset
                </a>
                <a href="{{ route('admin.peminjamans.index') }}" class="sidebar-link {{ request()->routeIs('admin.peminjamans.index') || request()->routeIs('admin.peminjamans.show') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                    Peminjaman
                </a>

                <p class="sidebar-section">Aksi Cepat</p>
                <a href="{{ route('admin.peminjamans.create') }}" class="sidebar-link {{ request()->routeIs('admin.peminjamans.create') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Buat Tiket Baru
                </a>
            </nav>

            {{-- User --}}
            <div class="px-4 py-3 border-t border-slate-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-brand-100 text-brand-700 rounded-lg flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-500 capitalize font-medium">{{ Auth::user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ═══ MAIN ═══ --}}
        <div class="flex-1 lg:ml-60 flex flex-col min-h-screen">
            {{-- Top Bar --}}
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/60 px-4 lg:px-6 py-3 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-3">
                    <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    </button>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs text-slate-400 mt-0.5 hidden sm:block">@yield('page-subtitle', '')</p>
                    </div>
                </div>
                <div class="text-xs text-slate-400 font-medium hidden sm:block">{{ now()->translatedFormat('l, d F Y') }}</div>
            </header>

            {{-- Flash --}}
            @if(session('success') || session('info') || session('error'))
            <div class="px-4 lg:px-6 pt-4">
                @if(session('success'))
                <div class="p-3.5 bg-emerald-50 border border-emerald-200/60 rounded-xl text-emerald-700 text-xs font-medium animate-fade-in-up flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('info'))
                <div class="p-3.5 bg-blue-50 border border-blue-200/60 rounded-xl text-blue-700 text-xs font-medium animate-fade-in-up flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    {{ session('info') }}
                </div>
                @endif
                @if(session('error'))
                <div class="p-3.5 bg-rose-50 border border-rose-200/60 rounded-xl text-rose-700 text-xs font-medium animate-fade-in-up flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
                @endif
            </div>
            @endif

            {{-- Content --}}
            <main class="flex-1 p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
