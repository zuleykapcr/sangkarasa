<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pesanan;

class DashboardController extends Controller
{
    public function index()
    {
        $stat = [
            'menu'        => Menu::count(),
            'pesanan'     => Pesanan::count(),
            'berlangsung' => Pesanan::berlangsung()->count(),
            'perlu_cek'   => Pesanan::where('status_pembayaran', '!=', 'Lunas')->count(),
            'pendapatan'  => (int) Pesanan::where('status_pembayaran', 'Lunas')->sum('total_biaya'),
        ];

        $terbaru = Pesanan::with('user')->latest()->take(5)->get();

        // Jadwal pengiriman: pesanan yang belum selesai, urut tanggal terdekat
        $jadwal = Pesanan::berlangsung()
            ->orderBy('tanggal_pengiriman')
            ->orderBy('waktu_pengiriman')
            ->take(5)
            ->get();

        // Laporan penjualan 7 hari terakhir
        $laporan = Pesanan::where('status_pembayaran', 'Lunas')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->get()
            ->groupBy(fn ($p) => $p->created_at->format('Y-m-d'))
            ->map(fn ($g) => ['jumlah' => $g->count(), 'total' => (int) $g->sum('total_biaya')])
            ->sortKeys();

        return view('admin.dashboard', compact('stat', 'terbaru', 'jadwal', 'laporan'));
    }
}