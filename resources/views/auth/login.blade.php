@extends('layouts.guest')
@section('content')
<div class="w-full max-w-sm animate-fade-in-up">
    <div class="text-center mb-8">
        <div class="w-14 h-14 bg-gradient-to-br from-brand-400 to-brand-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-brand-500/30">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <h1 class="text-xl font-extrabold text-white tracking-tight">MBS A.R. Fachruddin</h1>
        <p class="text-slate-400 text-xs mt-1">Sistem Pendukung Keputusan Peminjaman Fasilitas</p>
    </div>

    <div class="glass rounded-2xl p-7 shadow-2xl">
        <h2 class="text-base font-bold text-white mb-5">Masuk ke Akun Anda</h2>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@mbs.sch.id"
                    class="w-full rounded-lg border border-white/15 bg-white/5 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-brand-400 focus:ring-2 focus:ring-brand-400/20 focus:outline-none transition-all">
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-300 mb-1.5">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                    class="w-full rounded-lg border border-white/15 bg-white/5 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-brand-400 focus:ring-2 focus:ring-brand-400/20 focus:outline-none transition-all">
                @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded border-white/20 bg-white/5 text-brand-500 focus:ring-brand-500/20 w-3.5 h-3.5">
                <label for="remember" class="text-xs text-slate-300">Ingat saya</label>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white font-semibold py-2.5 rounded-lg shadow-lg shadow-brand-600/25 transition-all duration-300 text-sm cursor-pointer">
                Masuk
            </button>
        </form>
    </div>
    <p class="text-center text-slate-500/60 text-[10px] mt-6">&copy; {{ date('Y') }} MBS A.R. Fachruddin</p>
</div>
@endsection
