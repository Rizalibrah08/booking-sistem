<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    public function rules(): array
    {
        return [
            'nama_aset'                => ['required', 'string', 'max:255'],
            'kategori'                 => ['required', 'in:ruangan,alat'],
            'status'                   => ['required', 'in:tersedia,rusak,maintenance'],
            'is_restricted_for_student' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_aset.required' => 'Nama aset wajib diisi.',
            'kategori.required'  => 'Kategori wajib dipilih.',
            'kategori.in'        => 'Kategori harus Ruangan atau Alat.',
            'status.in'          => 'Status tidak valid.',
        ];
    }
}
