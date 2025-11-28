<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KaryawanUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'karyawan@fixplay.test'], // email unik
            [
                'name'     => 'Karyawan Fixplay',
                'password' => Hash::make('password-karyawan'), // ganti ke password yang aman
                'role'     => 'employee', // sesuaikan dengan kolom role di project kamu
            ]
        );
    }
}
