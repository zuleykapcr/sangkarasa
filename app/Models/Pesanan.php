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

    // Urutan tahap pesanan
    public const TAHAP = ['Menunggu', 'Sedang Dimasak', 'Dalam Pengiriman', 'Selesai'];

    // Label yang tampil untuk pelanggan
    public const LABEL_TAHAP = ['Dikonfirmasi', 'Sedang dimasak', 'Dalam pengiriman', 'Tiba di rumah'];

    protected function casts(): array
    {
        return ['tanggal_pengiriman' => 'date'];
    }

    // ===== RELASI =====
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    // ===== SCOPE =====
    public function scopeBerlangsung($query)
    {
        return $query->where('status_pesanan', '!=', 'Selesai');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status_pesanan', 'Selesai');
    }

    // ===== ACCESSOR =====
    // Posisi tahap saat ini (0 sampai 3)
    public function getTahapIndexAttribute(): int
    {
        $index = array_search($this->status_pesanan, self::TAHAP);

        return $index === false ? 0 : $index;
    }
}
