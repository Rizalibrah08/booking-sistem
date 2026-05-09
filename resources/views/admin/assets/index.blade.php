@extends('layouts.admin')
@section('title', 'Data Aset')
@section('page-title', 'Data Aset')
@section('page-subtitle', 'Kelola fasilitas dan peralatan sekolah')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-500">Total <strong>{{ $assets->total() }}</strong> aset</p>
        <a href="{{ route('admin.assets.create') }}" class="btn-primary">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Aset
        </a>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Nama Aset</th><th>Kategori</th><th>Status</th><th>Restricted</th><th class="text-center">Peminjaman</th><th class="text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($assets as $i => $asset)
                    <tr>
                        <td class="font-medium text-slate-400">{{ $assets->firstItem() + $i }}</td>
                        <td class="font-bold text-slate-800">{{ $asset->nama_aset }}</td>
                        <td>
                            <span class="inline-flex items-center gap-1 text-xs">
                                @if($asset->kategori === 'ruangan')🏫 @else 🔧 @endif
                                {{ ucfirst($asset->kategori) }}
                            </span>
                        </td>
                        <td><span class="badge-{{ $asset->status }}">{{ ucfirst($asset->status) }}</span></td>
                        <td>
                            @if($asset->is_restricted_for_student)
                                <span class="badge-rejected">Restricted</span>
                            @else
                                <span class="text-[11px] text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="text-center font-semibold text-slate-600">{{ $asset->peminjamans_count }}</td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.assets.edit', $asset) }}" class="px-2 py-1 text-[11px] font-semibold text-brand-600 hover:bg-brand-50 rounded-md transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.assets.destroy', $asset) }}" onsubmit="return confirm('Yakin hapus aset ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-[11px] font-semibold text-rose-600 hover:bg-rose-50 rounded-md transition-colors">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-slate-400 text-xs">Belum ada data aset</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($assets->hasPages())
        <div class="px-4 py-3 border-t border-slate-100">{{ $assets->links() }}</div>
        @endif
    </div>
</div>
@endsection
