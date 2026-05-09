<?php

namespace App\Http\Requests;

use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;

class StorePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_id'            => ['required', 'exists:assets,id'],
            'tgl_pakai'           => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'           => ['required', 'date_format:H:i'],
            'jam_selesai'         => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'tujuan'              => ['required', 'string', 'max:1000'],
            'urgensi_score'       => ['required', 'integer', 'in:1,2,3,4'],

            // Student borrower fields
            'is_student_borrower' => ['sometimes', 'boolean'],
            'nama_siswa'          => ['required_if:is_student_borrower,1', 'nullable', 'string', 'max:255'],
            'guarantor_id'        => ['required_if:is_student_borrower,1', 'nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'asset_id.required'       => 'Aset wajib dipilih.',
            'asset_id.exists'         => 'Aset tidak ditemukan.',
            'tgl_pakai.required'      => 'Tanggal pakai wajib diisi.',
            'tgl_pakai.after_or_equal' => 'Tanggal pakai tidak boleh di masa lalu.',
            'jam_mulai.required'      => 'Jam mulai wajib diisi.',
            'jam_selesai.required'    => 'Jam selesai wajib diisi.',
            'jam_selesai.after'       => 'Jam selesai harus setelah jam mulai.',
            'tujuan.required'         => 'Tujuan peminjaman wajib diisi.',
            'urgensi_score.required'  => 'Tingkat urgensi wajib dipilih.',
            'nama_siswa.required_if'  => 'Nama siswa wajib diisi untuk peminjaman siswa.',
            'guarantor_id.required_if' => 'Guru penanggung jawab wajib dipilih untuk peminjaman siswa.',
        ];
    }

    /**
     * Additional validation after standard rules pass.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $asset = null;

            // Load asset once if asset_id is provided
            if ($this->filled('asset_id')) {
                $asset = Asset::find($this->input('asset_id'));
            }

            // Cek restricted asset untuk siswa
            if ($this->boolean('is_student_borrower') && $asset && $asset->isRestrictedForStudent()) {
                $validator->errors()->add(
                    'asset_id',
                    "Aset \"{$asset->nama_aset}\" tidak dapat dipinjam oleh siswa (restricted)."
                );
            }

            // Cek aset tersedia
            if ($asset && ! $asset->isAvailable()) {
                $validator->errors()->add(
                    'asset_id',
                    "Aset \"{$asset->nama_aset}\" sedang tidak tersedia (status: {$asset->status})."
                );
            }
        });
    }
}
