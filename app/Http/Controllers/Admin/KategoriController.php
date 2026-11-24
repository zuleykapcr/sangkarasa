<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('menu')->get();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Kategori sudah ada.',
        ]);

        Kategori::create($data);

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $kategori->id,
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Kategori sudah ada.',
        ]);

        $kategori->update($data);

        return back()->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->menu()->exists()) {
            return back()->with('error', 'Kategori masih dipakai oleh menu, jadi tidak bisa dihapus.');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori dihapus.');
    }
}