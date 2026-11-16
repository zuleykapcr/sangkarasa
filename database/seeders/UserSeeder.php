<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
            'name' => 'Admin SangkaRasa',
            'username' => 'admin',
            'email' => 'admin@sangkarasa.test',
            'no_whatsapp' => '081200000000',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Zuleyka Keysha Zahra',
            'username' => 'zlykeysha',
            'email' => 'zuleyka@sangkarasa.test',
            'no_whatsapp' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }
}