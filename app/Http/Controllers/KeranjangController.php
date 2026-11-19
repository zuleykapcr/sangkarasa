<?php

namespace App\Http\Controllers;

use App\Support\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        $items = Keranjang::items();

        return view('keranjang.index', compact('items'));
    }

    public function update(Request $request, string $key)
    {
        $request->validate([
            'jumlah_porsi' => 'required|integer|min:1|max:100',
        ], [
            'jumlah_porsi.min' => 'Minimal 1 porsi.',
            'jumlah_porsi.max' => 'Maksimal 100 porsi.',
        ]);

        $keranjang = session('keranjang', []);

        if (isset($keranjang[$key])) {
            $keranjang[$key]['jumlah_porsi'] = (int) $request->jumlah_porsi;
            session(['keranjang' => $keranjang]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(string $key)
    {
        $keranjang = session('keranjang', []);
        unset($keranjang[$key]);
        session(['keranjang' => $keranjang]);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}