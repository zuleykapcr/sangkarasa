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
}
