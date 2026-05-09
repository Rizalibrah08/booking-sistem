@extends('layouts.guru')
@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Tiket')
@section('page-subtitle', 'Semua peminjaman yang pernah diajukan')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-500">Total <strong>{{ $peminjamans->total() }}</strong> tiket</p>
        <a href="{{ route('guru.peminjamans.create') }}" class="btn-success">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Ajukan Baru
        </a>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>Tiket</th><th>Aset</th><th>Tanggal</th><th>Waktu</th><th>Urgensi</th><th>Skor SAW</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse($peminjamans as $p)
                    <tr>
                        <td><span class="ticket-id">#TKT-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                        <td class="font-bold text-xs text-slate-800">{{ $p->asset->nama_aset }}</td>
                        <td class="text-xs whitespace-nowrap">{{ $p->tgl_pakai->format('d M Y') }}</td>
                        <td class="text-[11px] whitespace-nowrap">{{ \Carbon\Carbon::parse($p->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->jam_selesai)->format('H:i') }}</td>
                        <td>
                            @php $labels = [4=>'Ujian',3=>'KBM',2=>'Rapat',1=>'Ekskul']; @endphp
                            <span class="text-[11px]">{{ $labels[$p->urgensi_score] ?? '-' }}</span>
                        </td>
                        <td>
                            @if($p->saw_final_score)
                                <span class="font-mono text-[11px] font-bold text-brand-700">{{ number_format($p->saw_final_score, 4) }}</span>
                            @else
                                <span class="text-[11px] text-slate-300">—</span>
                            @endif
                        </td>
                        <td><span class="badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                        <td><a href="{{ route('guru.peminjamans.show', $p) }}" class="text-emerald-600 hover:text-emerald-800 text-[11px] font-bold">Detail →</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-8 text-slate-400 text-xs">Belum ada tiket peminjaman</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($peminjamans->hasPages())
        <div class="px-4 py-3 border-t border-slate-100">{{ $peminjamans->links() }}</div>
        @endif
    </div>
</div>
@endsection
