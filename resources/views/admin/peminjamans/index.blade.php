@extends('layouts.admin')
@section('title', 'Daftar Peminjaman')
@section('page-title', 'Peminjaman')
@section('page-subtitle', 'Kelola semua tiket peminjaman fasilitas')

@section('content')
<div class="space-y-4">
    {{-- Filter --}}
    <div class="card">
        <div class="card-body !py-3">
            <form method="GET" action="{{ route('admin.peminjamans.index') }}" class="flex flex-wrap items-end gap-2.5">
                <div class="min-w-[120px]">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</label>
                    <select name="status" class="form-select !py-1.5 text-xs mt-1">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</label>
                    <input type="date" name="tgl_pakai" value="{{ request('tgl_pakai') }}" class="form-input !py-1.5 text-xs mt-1">
                </div>
                <div class="min-w-[140px]">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aset</label>
                    <select name="asset_id" class="form-select !py-1.5 text-xs mt-1">
                        <option value="">Semua</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ request('asset_id') == $asset->id ? 'selected' : '' }}>{{ $asset->nama_aset }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary !py-1.5 text-xs">Filter</button>
                <a href="{{ route('admin.peminjamans.index') }}" class="btn-secondary !py-1.5 text-xs">Reset</a>
            </form>
        </div>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-500">Total <strong>{{ $peminjamans->total() }}</strong> tiket</p>
        <a href="{{ route('admin.peminjamans.create') }}" class="btn-primary">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Buat Tiket
        </a>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr><th>Tiket</th><th>Peminjam</th><th>Aset</th><th>Tanggal</th><th>Waktu</th><th>Urgensi</th><th>Skor SAW</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $p)
                    <tr>
                        <td><span class="ticket-id">#TKT-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <p class="font-bold text-slate-800 text-xs">{{ $p->user->name }}</p>
                            @if($p->is_student_borrower)
                                <p class="text-[10px] text-amber-600 font-medium">🎒 {{ $p->nama_siswa }}</p>
                            @else
                                <p class="text-[10px] text-slate-400 capitalize">{{ $p->user->role }}</p>
                            @endif
                        </td>
                        <td class="font-semibold text-xs">{{ $p->asset->nama_aset }}</td>
                        <td class="whitespace-nowrap text-xs">{{ $p->tgl_pakai->format('d M Y') }}</td>
                        <td class="whitespace-nowrap text-[11px]">{{ \Carbon\Carbon::parse($p->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->jam_selesai)->format('H:i') }}</td>
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
                        <td><a href="{{ route('admin.peminjamans.show', $p) }}" class="text-brand-600 hover:text-brand-800 text-[11px] font-bold">Detail →</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-8 text-slate-400 text-xs">Belum ada tiket peminjaman</td></tr>
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
