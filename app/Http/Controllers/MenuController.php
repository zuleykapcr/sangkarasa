<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Menu;

class MenuController extends Controller
{
   public function index(Request $request)
    {
        $query = Menu::with('kategori');

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

    // Menu lain dari kategori yang sama
    $menuLain = Menu::where('kategori_id', $menu->kategori_id)
        ->where('id', '!=', $menu->id)
        ->take(3)
        ->get();

    return view('menu.show', compact('menu', 'menuLain'));
}

public function pesan(Request $request, Menu $menu)
{
    $validated = $request->validate([
        'opsi_tambahan'       => 'required|in:Sambal terpisah,Tanpa sambal',
        'jumlah_porsi'        => 'required|integer|min:1|max:100',
        'tanggal_pengiriman'  => 'required|date|after_or_equal:tomorrow',
        'waktu_pengiriman'    => 'required|in:08.00-09.00 WIB,11.00-12.00 WIB,17.00-18.00 WIB',
    ], [
        'jumlah_porsi.min'                  => 'Minimal pesan 1 porsi.',
        'tanggal_pengiriman.after_or_equal' => 'Pesanan minimal H-1 sebelum hari pengiriman.',
        'tanggal_pengiriman.required'       => 'Tanggal pengiriman wajib diisi.',
    ]);

    session(['pesanan_sementara' => [
        'menu_id'  => $menu->id,
        'subtotal' => $menu->harga * $validated['jumlah_porsi'],
    ] + $validated]);

    return redirect()
        ->route('menu.show', $menu)
        ->with('success', 'Pesanan sementara tersimpan. Checkout akan aktif di tahap berikutnya.');
    }
}
