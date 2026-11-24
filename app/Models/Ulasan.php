<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';

    protected $fillable = ['user_id', 'menu_id', 'rating', 'komentar', 'tanggal'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}