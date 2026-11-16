<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Ayam', 'Daging', 'Ikan', 'Sayuran'] as $nama) {
            Kategori::create(['nama_kategori' => $nama]);
        }
    }
}