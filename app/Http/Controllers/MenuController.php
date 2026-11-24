<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('kategori')
            ->withAvg('ulasan', 'rating')
            ->withCount('ulasan');

        // SEARCH
        if ($request->filled('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        // FILTER kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // PAGINATION (6 per halaman, query string tetap terbawa)
        $menus = $query->latest()->paginate(6)->withQueryString();
        $kategori = Kategori::all();

        return view('menu.index', compact('menus', 'kategori'));
    }

    public function show(Menu $menu)
    {
        $menu->load('kategori');
        $menu->loadAvg('ulasan', 'rating');
        $menu->loadCount('ulasan');

        // Menu lain dari kategori yang sama
        $menuLain = Menu::with('kategori')
            ->withAvg('ulasan', 'rating')
            ->withCount('ulasan')
            ->where('kategori_id', $menu->kategori_id)
            ->where('id', '!=', $menu->id)
            ->take(3)
            ->get();

        // Daftar ulasan (5 per halaman)
        $ulasan = $menu->ulasan()->with('user')->latest()->paginate(5)->fragment('ulasan');

        // Siapa yang boleh menulis ulasan
        $user       = auth()->user();
        $bisaUlas   = $user && $user->role !== 'admin' && $menu->sudahDibeliOleh($user);
        $ulasanSaya = $user ? $menu->ulasan()->where('user_id', $user->id)->first() : null;

        return view('menu.show', compact('menu', 'menuLain', 'ulasan', 'bisaUlas', 'ulasanSaya'));
    }

    public function pesan(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'opsi_tambahan'      => 'required|in:Sambal terpisah,Tanpa sambal',
            'jumlah_porsi'       => 'required|integer|min:1|max:100',
            'tanggal_pengiriman' => 'required|date|after_or_equal:tomorrow',
            'waktu_pengiriman'   => 'required|in:08.00-09.00 WIB,11.00-12.00 WIB,17.00-18.00 WIB',
        ], [
            'jumlah_porsi.min'                  => 'Minimal pesan 1 porsi.',
            'tanggal_pengiriman.after_or_equal' => 'Pesanan minimal H-1 sebelum hari pengiriman.',
            'tanggal_pengiriman.required'       => 'Tanggal pengiriman wajib diisi.',
        ]);

        $keranjang = session('keranjang', []);
        $key = $menu->id . '_' . Str::slug($validated['opsi_tambahan'], '_');

        if (isset($keranjang[$key])) {
            $keranjang[$key]['jumlah_porsi'] = min(100, $keranjang[$key]['jumlah_porsi'] + $validated['jumlah_porsi']);
        } else {
            $keranjang[$key] = [
                'menu_id'       => $menu->id,
                'opsi_tambahan' => $validated['opsi_tambahan'],
                'jumlah_porsi'  => $validated['jumlah_porsi'],
            ];
        }

        // Jadwal dipakai sebagai isian awal di halaman checkout
        session([
            'keranjang'      => $keranjang,
            'jadwal_default' => [
                'tanggal_pengiriman' => $validated['tanggal_pengiriman'],
                'waktu_pengiriman'   => $validated['waktu_pengiriman'],
            ],
        ]);

        return redirect()->route('keranjang.index')
            ->with('success', $menu->nama_menu . ' masuk ke keranjang.');
    }
}