@extends('layouts.guest')
@section('content')
<div class="flex min-h-screen w-full bg-white">
    <!-- Left Column: Info & Branding -->
    <div class="hidden lg:flex w-1/2 relative bg-brand-50 overflow-hidden items-end p-12">
        <!-- Abstract waves/background -->
        <div class="absolute inset-0 z-0">
            <div class="absolute -bottom-1/2 -left-1/4 w-[150%] h-[150%] rounded-[100%] bg-gradient-to-tr from-brand-300 via-brand-200 to-brand-100 opacity-70 transform rotate-12"></div>
            <div class="absolute -bottom-1/3 -right-1/4 w-[120%] h-[120%] rounded-[100%] bg-gradient-to-tl from-brand-400 via-brand-300 to-transparent opacity-80 transform -rotate-6"></div>
        </div>
        
        <!-- Info Card -->
        <div class="relative z-10 w-full max-w-lg bg-brand-900 rounded-3xl p-10 shadow-2xl shadow-brand-900/30 text-white mb-8 border border-white/10">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-2.5 h-2.5 rounded-full bg-brand-400 animate-pulse"></div>
                <span class="text-xs font-bold tracking-widest text-brand-200 uppercase">SPK Fasilitas</span>
            </div>
            <h1 class="text-4xl font-black mb-4 tracking-tight">MBS A.R. Fachruddin</h1>
            <p class="text-brand-100/80 leading-relaxed text-sm">
                Transformasi layanan pendidikan tinggi digital terintegrasi untuk masa depan yang lebih cerdas dan efisien.
            </p>
        </div>
        
        <!-- Top Left Logo Placeholder -->
        <div class="absolute top-8 left-8 z-10 flex items-center gap-4 bg-white/80 backdrop-blur-md px-6 py-3 rounded-2xl shadow-sm border border-white/50">
            <img src="{{ asset('image/logo/Logo SMP Transparan.png') }}" alt="Logo SMP" class="w-10 h-10 object-contain drop-shadow-sm">
            <div class="font-bold text-slate-800 tracking-tight text-sm">Peminjaman <span class="text-brand-600">MBS</span></div>
        </div>
    </div>

    <!-- Right Column: Login Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 sm:p-12 lg:p-24 relative z-10">
        <div class="w-full max-w-sm animate-fade-in-up">
            <div class="mb-10 text-center lg:text-left">
                <div class="lg:hidden flex items-center justify-center gap-3 mb-8">
                    <img src="{{ asset('image/logo/Logo SMP Transparan.png') }}" alt="Logo SMP" class="w-10 h-10 object-contain drop-shadow-sm">
                    <div class="font-bold text-slate-800 tracking-tight text-xl">Peminjaman <span class="text-brand-600">MBS</span></div>
                </div>
                
                <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-3">Login</h2>
                <p class="text-slate-500 text-sm leading-relaxed">Selamat datang. Silakan masukkan kredensial Anda untuk mengelola sistem.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-[11px] font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan username"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-11 px-4 py-3.5 text-sm text-slate-900 placeholder-slate-400 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:outline-none transition-all">
                    </div>
                    @error('email')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">Password</label>
                        <!-- <a href="#" class="text-[11px] font-bold text-brand-600 hover:text-brand-700 transition-colors">Lupa Password?</a> -->
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-11 pr-11 px-4 py-3.5 text-sm text-slate-900 placeholder-slate-400 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:outline-none transition-all [&::-ms-reveal]:hidden">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                            <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    @error('password')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>
                

                <div class="pt-4">
                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-brand-600/25 hover:shadow-xl hover:shadow-brand-600/35 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                        <span>Login</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
            
            <div class="mt-16 text-center lg:text-left">
                <p class="text-slate-400 text-[11px] font-medium mb-1">Versi Sistem 2.1 </p>
                <p class="text-slate-400 text-[11px] font-medium">&copy; {{ date('Y') }} Sistem Pendukung Keputusan Peminjaman Fasilitas</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        }
    });
</script>
@endsection
