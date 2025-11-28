<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'bos@fixplay.test'], // email unik
            [
                'name'     => 'Bos Fixplay',
                'password' => Hash::make('password-bos'), // ganti ke password yang aman
                'role'     => 'boss', // sesuaikan dengan kolom role di project kamu
            ]
        );
    }
}
