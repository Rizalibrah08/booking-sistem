@extends('layouts.admin')
@section('title', 'Detail Tiket #' . $peminjaman->id)
@section('page-title', 'Detail Tiket')
@section('page-subtitle', '#TKT-' . str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-3xl space-y-4">

    {{-- Ticket Card --}}
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
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Peminjam</p>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $peminjaman->user->name }}</p>
                    <p class="text-[11px] text-slate-400 capitalize">{{ $peminjaman->user->role }}</p>
                </div>
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
            </div>
            <div class="mt-4 pt-3 border-t border-dashed border-slate-200">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tujuan</p>
                <p class="text-xs text-slate-600 mt-1">{{ $peminjaman->tujuan }}</p>
            </div>

            @if($peminjaman->is_student_borrower)
            <div class="mt-3 p-3 bg-amber-50 rounded-lg border border-amber-200/60">
                <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1.5">🎒 Peminjaman Siswa</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] text-amber-600">Nama Siswa</p>
                        <p class="text-xs font-bold text-amber-800">{{ $peminjaman->nama_siswa }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-amber-600">Guru Penanggung Jawab</p>
                        <p class="text-xs font-bold text-amber-800">{{ $peminjaman->guarantor->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- SAW Score --}}
    <div class="card animate-fade-in-up" style="animation-delay:100ms">
        <div class="card-header">
            <h3 class="text-sm font-bold text-slate-800">📊 Skor SAW</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @php $maxScore = max($peminjaman->getEffectiveJabatanScore(), 1); @endphp
                <div class="p-3 bg-brand-50 rounded-lg text-center">
                    <p class="text-[10px] font-bold text-brand-600 uppercase tracking-wider">C1: Jabatan</p>
                    <p class="text-xl font-extrabold text-brand-700 mt-1">{{ $peminjaman->getEffectiveJabatanScore() }}</p>
                    <div class="saw-meter mt-2"><div class="saw-meter-fill bg-brand-500" style="width:{{ ($peminjaman->getEffectiveJabatanScore() / 4) * 100 }}%"></div></div>
                    <p class="text-[9px] text-slate-400 mt-1">Bobot: 0.40</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-lg text-center">
                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">C2: Urgensi</p>
                    <p class="text-xl font-extrabold text-amber-700 mt-1">{{ $peminjaman->urgensi_score }}</p>
                    <div class="saw-meter mt-2"><div class="saw-meter-fill bg-amber-500" style="width:{{ ($peminjaman->urgensi_score / 4) * 100 }}%"></div></div>
                    <p class="text-[9px] text-slate-400 mt-1">Bobot: 0.35</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-lg text-center">
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">C3: Lead Time</p>
                    <p class="text-xl font-extrabold text-emerald-700 mt-1">{{ $peminjaman->lead_time_score }}</p>
                    <div class="saw-meter mt-2"><div class="saw-meter-fill bg-emerald-500" style="width:{{ ($peminjaman->lead_time_score / 3) * 100 }}%"></div></div>
                    <p class="text-[9px] text-slate-400 mt-1">Bobot: 0.25</p>
                </div>
                <div class="p-3 bg-slate-800 rounded-lg text-center">
                    <p class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">Skor Akhir</p>
                    <p class="text-xl font-extrabold text-white mt-1">{{ $peminjaman->saw_final_score ? number_format($peminjaman->saw_final_score, 4) : '—' }}</p>
                    <p class="text-[9px] text-slate-400 mt-2">V = Σ(w × r)</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Conflicts --}}
    @if($conflicts->count() > 0)
    <div class="card border-rose-200 animate-fade-in-up" style="animation-delay:200ms">
        <div class="card-header bg-rose-50">
            <h3 class="text-sm font-bold text-rose-800">⚡ Tiket Konflik ({{ $conflicts->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>Tiket</th><th>Peminjam</th><th>Waktu</th><th>Jabatan</th><th>Urgensi</th><th>Lead</th><th>Skor</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($conflicts as $c)
                    <tr>
                        <td><span class="ticket-id">#TKT-{{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                        <td class="font-bold text-xs">{{ $c->user->name }}</td>
                        <td class="text-[11px]">{{ \Carbon\Carbon::parse($c->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($c->jam_selesai)->format('H:i') }}</td>
                        <td class="text-center font-bold">{{ $c->getEffectiveJabatanScore() }}</td>
                        <td class="text-center font-bold">{{ $c->urgensi_score }}</td>
                        <td class="text-center font-bold">{{ $c->lead_time_score }}</td>
                        <td class="font-mono text-[11px] font-bold">{{ $c->saw_final_score ? number_format($c->saw_final_score, 4) : '—' }}</td>
                        <td><span class="badge-{{ $c->status }}">{{ ucfirst($c->status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Cancel Reason --}}
    @if($peminjaman->cancel_reason)
    <div class="p-3.5 bg-rose-50 rounded-xl border border-rose-200/60">
        <p class="text-xs font-bold text-rose-800 mb-1">Alasan Pembatalan:</p>
        <p class="text-xs text-rose-700">{{ $peminjaman->cancel_reason }}</p>
    </div>
    @endif

    {{-- Force Cancel --}}
    @if($peminjaman->status === 'approved')
    <div class="card border-rose-200 animate-fade-in-up" style="animation-delay:300ms">
        <div class="card-header bg-rose-50">
            <h3 class="text-sm font-bold text-rose-800">🚫 Force Cancel</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.peminjamans.cancel', $peminjaman) }}" onsubmit="return confirm('Yakin batalkan tiket ini?')">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label for="cancel_reason" class="form-label">Alasan Pembatalan</label>
                    <textarea id="cancel_reason" name="cancel_reason" rows="2" class="form-input" required placeholder="Wajib diisi..."></textarea>
                    @error('cancel_reason')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-danger">Batalkan Tiket</button>
            </form>
        </div>
    </div>
    @endif

    <a href="{{ route('admin.peminjamans.index') }}" class="btn-secondary">← Kembali</a>
</div>
@endsection
