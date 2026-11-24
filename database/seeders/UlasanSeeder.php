<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Database\Seeder;

class UlasanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('username', 'zlykeysha')->first();

        if (!$user) {
            return;
        }

        $data = [
            ['Ayam bakar rempah', 5, 'Bumbunya terasa sampai dalam. Sambalnya dipisah, jadi anak-anak juga bisa ikut makan.'],
            ['Rendang sapi', 5, 'Empuk dan kaya rasa, mirip masakan rumah.'],
            ['Pepes ikan kemangi', 4, 'Harum kemangi dan ikannya segar.'],
            ['Sayur asem', 4, 'Segar, cocok untuk makan siang.'],
        ];

        foreach ($data as [$nama, $rating, $komentar]) {
            $menu = Menu::where('nama_menu', $nama)->first();

            if ($menu) {
                Ulasan::updateOrCreate(
                    ['user_id' => $user->id, 'menu_id' => $menu->id],
                    ['rating' => $rating, 'komentar' => $komentar, 'tanggal' => now()->toDateString()]
                );
            }
        }
    }
}