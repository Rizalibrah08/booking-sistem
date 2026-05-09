<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'           => 'Administrator',
                'email'          => 'admin@mbs.sch.id',
                'password'       => Hash::make('password'),
                'role'           => 'admin',
                'jabatan_score'  => 0, // Admin tidak ikut SAW
            ],
            [
                'name'           => 'Kepala Sekolah',
                'email'          => 'kepsek@mbs.sch.id',
                'password'       => Hash::make('password'),
                'role'           => 'kepsek',
                'jabatan_score'  => 4,
            ],
            [
                'name'           => 'Budi Santoso',
                'email'          => 'budi@mbs.sch.id',
                'password'       => Hash::make('password'),
                'role'           => 'guru',
                'jabatan_score'  => 3,
            ],
            [
                'name'           => 'Siti Rahayu',
                'email'          => 'siti@mbs.sch.id',
                'password'       => Hash::make('password'),
                'role'           => 'guru',
                'jabatan_score'  => 3,
            ],
            [
                'name'           => 'Ahmad Dahlan',
                'email'          => 'ahmad@mbs.sch.id',
                'password'       => Hash::make('password'),
                'role'           => 'guru',
                'jabatan_score'  => 3,
            ],
            [
                'name'           => 'Dewi Lestari',
                'email'          => 'dewi@mbs.sch.id',
                'password'       => Hash::make('password'),
                'role'           => 'staf',
                'jabatan_score'  => 2,
            ],
            [
                'name'           => 'Rian Pratama',
                'email'          => 'rian@mbs.sch.id',
                'password'       => Hash::make('password'),
                'role'           => 'staf',
                'jabatan_score'  => 2,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
