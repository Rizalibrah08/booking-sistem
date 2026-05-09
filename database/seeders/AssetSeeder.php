<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            [
                'nama_aset'                => 'Aula Utama',
                'kategori'                 => 'ruangan',
                'status'                   => 'tersedia',
                'is_restricted_for_student' => false,
            ],
            [
                'nama_aset'                => 'Lab Komputer',
                'kategori'                 => 'ruangan',
                'status'                   => 'tersedia',
                'is_restricted_for_student' => false,
            ],
            [
                'nama_aset'                => 'Ruang Rapat',
                'kategori'                 => 'ruangan',
                'status'                   => 'tersedia',
                'is_restricted_for_student' => true,
            ],
            [
                'nama_aset'                => 'Lab IPA',
                'kategori'                 => 'ruangan',
                'status'                   => 'tersedia',
                'is_restricted_for_student' => false,
            ],
            [
                'nama_aset'                => 'Lapangan Futsal',
                'kategori'                 => 'ruangan',
                'status'                   => 'tersedia',
                'is_restricted_for_student' => false,
            ],
            [
                'nama_aset'                => 'Proyektor Epson EB-X51',
                'kategori'                 => 'alat',
                'status'                   => 'tersedia',
                'is_restricted_for_student' => true,
            ],
            [
                'nama_aset'                => 'Sound System Portable',
                'kategori'                 => 'alat',
                'status'                   => 'tersedia',
                'is_restricted_for_student' => true,
            ],
            [
                'nama_aset'                => 'Kamera DSLR Canon',
                'kategori'                 => 'alat',
                'status'                   => 'maintenance',
                'is_restricted_for_student' => true,
            ],
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }
}
