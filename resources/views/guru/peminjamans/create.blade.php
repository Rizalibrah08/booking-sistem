@extends('layouts.guru')
@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman')
@section('page-subtitle', 'Isi form untuk meminjam fasilitas sekolah')

@section('content')
<div class="max-w-xl space-y-4">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('guru.peminjamans.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="asset_id" class="form-label">Pilih Aset / Fasilitas</label>
                    <select id="asset_id" name="asset_id" class="form-select" required>
                        <option value="">— Pilih Aset —</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                            {{ $asset->nama_aset }} ({{ ucfirst($asset->kategori) }})
                        </option>
                        @endforeach
                    </select>
                    @error('asset_id')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label for="tgl_pakai" class="form-label">Tanggal</label>
                        <input type="date" id="tgl_pakai" name="tgl_pakai" value="{{ old('tgl_pakai') }}" class="form-input" min="{{ date('Y-m-d') }}" required>
                        @error('tgl_pakai')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" class="form-input" required>
                        @error('jam_mulai')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" class="form-input" required>
                        @error('jam_selesai')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="tujuan" class="form-label">Tujuan Peminjaman</label>
                    <textarea id="tujuan" name="tujuan" rows="2" class="form-input" placeholder="Jelaskan tujuan peminjaman..." required>{{ old('tujuan') }}</textarea>
                    @error('tujuan')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="urgensi_score" class="form-label">Tingkat Urgensi</label>
                    <select id="urgensi_score" name="urgensi_score" class="form-select" required>
                        <option value="">— Pilih —</option>
                        <option value="4" {{ old('urgensi_score') == '4' ? 'selected' : '' }}>🔴 Ujian (Skor 4)</option>
                        <option value="3" {{ old('urgensi_score') == '3' ? 'selected' : '' }}>🟠 KBM (Skor 3)</option>
                        <option value="2" {{ old('urgensi_score') == '2' ? 'selected' : '' }}>🟡 Rapat (Skor 2)</option>
                        <option value="1" {{ old('urgensi_score') == '1' ? 'selected' : '' }}>🟢 Ekskul (Skor 1)</option>
                    </select>
                    @error('urgensi_score')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="submit" class="btn-success">Ajukan Tiket</button>
                    <a href="{{ route('guru.peminjamans.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="p-3.5 bg-emerald-50 rounded-xl border border-emerald-200/60">
        <p class="text-xs font-bold text-emerald-800 mb-1.5">ℹ️ Info</p>
        <ul class="text-[11px] text-emerald-700 space-y-0.5">
            <li>• Lead time dihitung otomatis dari selisih hari pengajuan</li>
            <li>• Jika ada konflik jadwal, SAW menentukan prioritas otomatis</li>
            <li>• Jika tidak ada konflik, tiket langsung disetujui</li>
        </ul>
    </div>
</div>
@endsection
