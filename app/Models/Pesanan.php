<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'kode_pesanan', 'user_id', 'nama_penerima', 'alamat_pengiriman', 'kota',
        'kode_pos', 'tanggal_pengiriman', 'waktu_pengiriman', 'catatan_pesanan',
        'metode_pembayaran', 'ongkos_kirim', 'potongan_diskon', 'total_biaya',
        'status_pembayaran', 'status_pesanan',
    ];

    protected function casts(): array
    {
        return ['tanggal_pengiriman' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
}