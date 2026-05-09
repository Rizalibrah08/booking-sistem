@extends('layouts.admin')
@section('title', 'Tambah Aset')
@section('page-title', 'Tambah Aset Baru')
@section('page-subtitle', 'Tambahkan fasilitas atau peralatan baru ke sistem')

@section('content')
<div class="max-w-xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.assets.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="nama_aset" class="form-label">Nama Aset</label>
                    <input type="text" id="nama_aset" name="nama_aset" value="{{ old('nama_aset') }}" class="form-input" placeholder="cth: Aula Utama, Proyektor Epson" required>
                    @error('nama_aset')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="kategori" class="form-label">Kategori</label>
                        <select id="kategori" name="kategori" class="form-select" required>
                            <option value="">— Pilih —</option>
                            <option value="ruangan" {{ old('kategori') === 'ruangan' ? 'selected' : '' }}>🏫 Ruangan</option>
                            <option value="alat" {{ old('kategori') === 'alat' ? 'selected' : '' }}>🔧 Alat</option>
                        </select>
                        @error('kategori')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="tersedia" {{ old('status', 'tersedia') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="rusak" {{ old('status') === 'rusak' ? 'selected' : '' }}>Rusak</option>
                            <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-lg border border-amber-200/60">
                    <input type="checkbox" id="is_restricted_for_student" name="is_restricted_for_student" value="1" {{ old('is_restricted_for_student') ? 'checked' : '' }} class="rounded border-amber-300 text-amber-600 focus:ring-amber-500 w-4 h-4">
                    <div>
                        <label for="is_restricted_for_student" class="text-xs font-semibold text-amber-800 cursor-pointer">Restricted untuk Siswa</label>
                        <p class="text-[10px] text-amber-600 mt-0.5">Siswa tidak dapat meminjam aset ini</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="submit" class="btn-primary">Simpan Aset</button>
                    <a href="{{ route('admin.assets.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
