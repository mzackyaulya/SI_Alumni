<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WakaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'waka@example.com'], // key unik
            [
                'name'     => 'Waka Kesiswaan',
                'password' => Hash::make('waka123'), // boleh diganti
                'role'     => 'waka',
            ]
        );
    }
}
