<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    // Daftar riwayat pesanan milik user yang login
    public function index(Request $request)
    {
        $filter = $request->query('status');
        $user   = auth()->user();

        $pesanan = $user->pesanan()
            ->with('detail.menu')
            ->when($filter === 'berlangsung', fn ($q) => $q->berlangsung())
            ->when($filter === 'selesai', fn ($q) => $q->selesai())
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $jumlah = [
            'semua'       => $user->pesanan()->count(),
            'berlangsung' => $user->pesanan()->berlangsung()->count(),
            'selesai'     => $user->pesanan()->selesai()->count(),
        ];

        return view('pesanan.index', compact('pesanan', 'jumlah', 'filter'));
    }

    // Detail satu pesanan
    public function show(Pesanan $pesanan)
    {
        abort_unless($pesanan->user_id === auth()->id(), 403);

        $pesanan->load('detail.menu');

        return view('pesanan.show', compact('pesanan'));
    }

    // Pesan lagi: item pesanan lama dimasukkan ke keranjang
    public function ulang(Pesanan $pesanan)
    {
        abort_unless($pesanan->user_id === auth()->id(), 403);

        $keranjang = session('keranjang', []);

        foreach ($pesanan->detail()->with('menu')->get() as $d) {
            if (!$d->menu) {
                continue;
            }

            $opsi = $d->opsi_tambahan ?? 'Sambal terpisah';
            $key  = $d->menu_id . '_' . Str::slug($opsi, '_');

            if (isset($keranjang[$key])) {
                $keranjang[$key]['jumlah_porsi'] = min(100, $keranjang[$key]['jumlah_porsi'] + $d->jumlah_porsi);
            } else {
                $keranjang[$key] = [
                    'menu_id'       => $d->menu_id,
                    'opsi_tambahan' => $opsi,
                    'jumlah_porsi'  => $d->jumlah_porsi,
                ];
            }
        }

        session(['keranjang' => $keranjang]);

        return redirect()->route('keranjang.index')
            ->with('success', 'Menu dari pesanan ' . $pesanan->kode_pesanan . ' masuk ke keranjang.');
    }
}
