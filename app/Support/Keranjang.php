<?php

namespace App\Support;

use App\Models\Menu;
use Illuminate\Support\Collection;

class Keranjang
{
    public static function raw(): array
    {
        return session('keranjang', []);
    }

    // Total porsi di keranjang (untuk badge navbar)
    public static function count(): int
    {
        return (int) collect(self::raw())->sum('jumlah_porsi');
    }

    // Item keranjang lengkap dengan data menu
    public static function items(): Collection
    {
        $raw = self::raw();
        $menus = Menu::whereIn('id', collect($raw)->pluck('menu_id'))->get()->keyBy('id');

        return collect($raw)->map(function ($row, $key) use ($menus) {
            $menu = $menus->get($row['menu_id']);
            if (!$menu) {
                return null;
            }

            return [
                'key'      => $key,
                'menu'     => $menu,
                'opsi'     => $row['opsi_tambahan'],
                'porsi'    => $row['jumlah_porsi'],
                'subtotal' => (int) $menu->harga * $row['jumlah_porsi'],
            ];
        })->filter();
    }

    // Hitung subtotal, ongkir, potongan voucher, dan total
    public static function ringkasan(Collection $items, ?string $kodeVoucher = null): array
    {
        $subtotal = (int) $items->sum('subtotal');
        $ongkir   = $items->isEmpty() ? 0 : (int) config('sangkarasa.ongkir');
        $potongan = 0;
        $voucher  = null;

        $v = $kodeVoucher ? config('sangkarasa.vouchers.' . $kodeVoucher) : null;

        if ($v && $subtotal >= $v['min_belanja']) {
            $potongan = min($v['potongan'], $subtotal);
            $voucher  = ['kode' => $kodeVoucher, 'nama' => $v['nama']];
        }

        return [
            'subtotal' => $subtotal,
            'ongkir'   => $ongkir,
            'potongan' => $potongan,
            'total'    => $subtotal + $ongkir - $potongan,
            'voucher'  => $voucher,
        ];
    }
}