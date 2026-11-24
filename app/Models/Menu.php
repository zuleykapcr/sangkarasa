<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $fillable = ['kategori_id', 'nama_menu', 'deskripsi', 'harga', 'gambar'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    // Gambar hasil upload (storage) atau gambar bawaan seeder (public/images/menu)
    public function getGambarUrlAttribute(): string
    {
        if (!$this->gambar) {
            return asset('images/hero.jpg');
        }

        if (str_starts_with($this->gambar, 'menu/')) {
            return asset('storage/' . $this->gambar);
        }

        return asset('images/menu/' . $this->gambar);
    }

    // True jika user pernah memesan menu ini dan pesanannya sudah Selesai
    public function sudahDibeliOleh(User $user): bool
    {
        return DetailPesanan::where('menu_id', $this->id)
            ->whereHas('pesanan', fn ($q) => $q
                ->where('user_id', $user->id)
                ->where('status_pesanan', 'Selesai'))
            ->exists();
    }
}