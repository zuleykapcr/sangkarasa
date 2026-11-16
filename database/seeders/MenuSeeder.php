<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [1, 'Ayam bakar rempah', 'Bumbu meresap, sambal bikin nagih!.', 35000, 'ayam-bakar-rempah.jpg'],
            [1, 'Ayam goreng lengkuas', 'Renyah di luar, gurih sampai ke tulang.', 32000, 'ayam-goreng-lengkuas.jpg'],
            [2, 'Rendang sapi', 'Dimasak perlahan, kaya rasa Minang.', 45000, 'rendang-sapi.jpg'],
            [2, 'Semur daging', 'Manis gurih, empuk di setiap suapan.', 42000, 'semur-daging.jpg'],
            [3, 'Pepes ikan kemangi', 'Harum kemangi dalam daun pisang.', 38000, 'pepes-ikan.jpg'],
            [3, 'Gulai ikan patin', 'Kuah kuning kental, segar rempahnya.', 40000, 'gulai-patin.jpg'],
            [4, 'Sayur asem', 'Segar asam manisnya, cocok untuk siang.', 18000, 'sayur-asem.jpg'],
            [4, 'Tumis kangkung belacan', 'Renyah dan wangi belacan.', 16000, 'kangkung.jpg'],
        ];

        foreach ($data as [$kategori, $nama, $deskripsi, $harga, $gambar]) {
            Menu::create([
                'kategori_id' => $kategori,
                'nama_menu' => $nama,
                'deskripsi' => $deskripsi,
                'harga' => $harga,
                'gambar' => $gambar,
            ]);
        }
    }
}