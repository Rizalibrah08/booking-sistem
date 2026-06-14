@extends('layouts.guru')
@section('title', 'Detail Tiket #' . $peminjaman->id)
@section('page-title', 'Detail Tiket')
@section('page-subtitle', '#TKT-' . str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-3xl space-y-4">

    {{-- Ticket --}}
    <div class="ticket-card animate-fade-in-up">
        <div class="ticket-card-header">
            <div class="flex items-center gap-3">
                <span class="ticket-id">#TKT-{{ str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT) }}</span>
                <span class="badge-{{ $peminjaman->status }} text-xs">{{ ucfirst($peminjaman->status) }}</span>
            </div>
            <p class="text-[11px] text-slate-400">{{ $peminjaman->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="ticket-card-body">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Aset</p>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $peminjaman->asset->nama_aset }}</p>
                    <p class="text-[11px] text-slate-400 capitalize">{{ $peminjaman->asset->kategori }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jadwal</p>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $peminjaman->tgl_pakai->format('d M Y') }}</p>
                    <p class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }} — {{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Urgensi</p>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $peminjaman->getUrgensiLabel() }}</p>
                    <p class="text-[11px] text-slate-400">Skor: {{ $peminjaman->urgensi_score }}</p>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-dashed border-slate-200">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tujuan</p>
                <p class="text-xs text-slate-600 mt-1">{{ $peminjaman->tujuan }}</p>
            </div>
        </div>
    </div>

    {{-- SAW --}}
    <div class="card animate-fade-in-up" style="animation-delay:100ms">
        <div class="card-header">
            <h3 class="text-sm font-bold text-slate-800">📊 Skor SAW</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3 bg-brand-50 rounded-lg text-center">
                    <p class="text-[10px] font-bold text-brand-600 uppercase tracking-wider">C1: Jabatan</p>
                    <p class="text-xl font-extrabold text-brand-700 mt-1">{{ $peminjaman->getEffectiveJabatanScore() }}</p>
                    <div class="saw-meter mt-2"><div class="saw-meter-fill bg-brand-500" style="width:{{ ($peminjaman->getEffectiveJabatanScore() / 4) * 100 }}%"></div></div>
                </div>
                <div class="p-3 bg-amber-50 rounded-lg text-center">
                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">C2: Urgensi</p>
                    <p class="text-xl font-extrabold text-amber-700 mt-1">{{ $peminjaman->urgensi_score }}</p>
                    <div class="saw-meter mt-2"><div class="saw-meter-fill bg-amber-500" style="width:{{ ($peminjaman->urgensi_score / 4) * 100 }}%"></div></div>
                </div>
                <div class="p-3 bg-emerald-50 rounded-lg text-center">
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">C3: Lead Time</p>
                    <p class="text-xl font-extrabold text-emerald-700 mt-1">{{ $peminjaman->lead_time_score }}</p>
                    <div class="saw-meter mt-2"><div class="saw-meter-fill bg-emerald-500" style="width:{{ ($peminjaman->lead_time_score / 3) * 100 }}%"></div></div>
                </div>
                <div class="p-3 bg-slate-800 rounded-lg text-center">
                    <p class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">Skor Akhir</p>
                    <p class="text-xl font-extrabold text-white mt-1">{{ $peminjaman->saw_final_score ? number_format($peminjaman->saw_final_score, 4) : '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($peminjaman->cancel_reason)
    <div class="p-3.5 bg-rose-50 rounded-xl border border-rose-200/60">
        <p class="text-xs font-bold text-rose-800 mb-1">Alasan Pembatalan / Penolakan:</p>
        <p class="text-xs text-rose-700">{{ $peminjaman->cancel_reason }}</p>
    </div>
    @endif

    <div class="flex items-center justify-between gap-3 mt-6">
        <a href="{{ route('guru.peminjamans.index') }}" class="btn-secondary">← Kembali</a>

        @if(in_array($peminjaman->status, ['pending', 'approved']))
        <button type="button" onclick="document.getElementById('cancelModal').classList.remove('hidden')" class="btn bg-rose-600 hover:bg-rose-700 text-white font-bold px-4 py-2 text-sm rounded-xl transition-all">
            Batalkan Peminjaman
        </button>
        @endif
    </div>
</div>

{{-- Modal Pembatalan --}}
@if(in_array($peminjaman->status, ['pending', 'approved']))
<div id="cancelModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-rose-50">
            <h3 class="font-bold text-rose-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Batalkan Peminjaman
            </h3>
            <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden')" class="text-rose-400 hover:text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('guru.peminjamans.cancel', $peminjaman) }}" method="POST" class="p-5">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="cancel_reason" class="block text-xs font-bold text-slate-700 mb-1">Alasan Pembatalan <span class="text-rose-500">*</span></label>
                <textarea name="cancel_reason" id="cancel_reason" rows="3" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition-all"
                    placeholder="Kenapa Anda membatalkan peminjaman ini?"></textarea>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden')" class="btn-secondary">Tutup</button>
                <button type="submit" class="btn bg-rose-600 hover:bg-rose-700 text-white font-bold px-4 py-2 text-sm rounded-xl">Konfirmasi Batal</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
