<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
          ['email' => 'admin@demo.test'],
          ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::updateOrCreate(
          ['email' => 'mhs@demo.test'],
          ['name' => 'Budi Santoso', 'password' => Hash::make('password'), 'role' => 'mahasiswa']
        );
    }
}