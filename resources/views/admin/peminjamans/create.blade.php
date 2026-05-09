@extends('layouts.admin')
@section('title', 'Buat Tiket Peminjaman')
@section('page-title', 'Buat Tiket Baru')
@section('page-subtitle', 'Ajukan peminjaman fasilitas untuk guru, staf, atau siswa')

@section('content')
<div class="max-w-xl space-y-4">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.peminjamans.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="asset_id" class="form-label">Pilih Aset / Fasilitas</label>
                    <select id="asset_id" name="asset_id" class="form-select" required>
                        <option value="">— Pilih Aset —</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                            {{ $asset->nama_aset }} ({{ ucfirst($asset->kategori) }})@if($asset->is_restricted_for_student) — ⚠ Restricted @endif
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
                    <textarea id="tujuan" name="tujuan" rows="2" class="form-input" placeholder="Jelaskan tujuan..." required>{{ old('tujuan') }}</textarea>
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

                {{-- Student --}}
                <div class="p-3 bg-amber-50 rounded-lg border border-amber-200/60">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="is_student_borrower" name="is_student_borrower" value="1"
                            {{ old('is_student_borrower') ? 'checked' : '' }}
                            class="rounded border-amber-300 text-amber-600 focus:ring-amber-500 w-4 h-4"
                            onchange="document.getElementById('student-fields').classList.toggle('hidden', !this.checked)">
                        <div>
                            <label for="is_student_borrower" class="text-xs font-semibold text-amber-800 cursor-pointer">Peminjaman untuk Siswa</label>
                            <p class="text-[10px] text-amber-600 mt-0.5">Centang jika diajukan atas nama siswa</p>
                        </div>
                    </div>
                </div>

                <div id="student-fields" class="space-y-3 {{ old('is_student_borrower') ? '' : 'hidden' }}">
                    <div>
                        <label for="nama_siswa" class="form-label">Nama Siswa</label>
                        <input type="text" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa') }}" class="form-input" placeholder="Nama lengkap siswa">
                        @error('nama_siswa')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="guarantor_id" class="form-label">Guru Penanggung Jawab</label>
                        <select id="guarantor_id" name="guarantor_id" class="form-select">
                            <option value="">— Pilih Guru —</option>
                            @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('guarantor_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Bobot jabatan guru digunakan dalam perhitungan SAW</p>
                        @error('guarantor_id')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="submit" class="btn-primary">Ajukan Tiket</button>
                    <a href="{{ route('admin.peminjamans.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="p-3.5 bg-blue-50 rounded-xl border border-blue-200/60">
        <p class="text-xs font-bold text-blue-800 mb-1.5">ℹ️ Informasi SAW</p>
        <ul class="text-[11px] text-blue-700 space-y-0.5">
            <li>• <strong>Lead Time</strong> dihitung otomatis dari selisih hari pengajuan</li>
            <li>• Jika ada <strong>konflik jadwal</strong>, SAW menentukan prioritas otomatis</li>
            <li>• <strong>Kepsek</strong> memiliki Hak Veto — langsung disetujui</li>
        </ul>
    </div>
</div>
@endsection
