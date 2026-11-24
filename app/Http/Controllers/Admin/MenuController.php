<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::with('kategori')
            ->when($request->filled('search'), fn ($q) => $q->where('nama_menu', 'like', '%' . $request->search . '%'))
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', $request->kategori))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $kategori = Kategori::all();

        return view('admin.menu.index', compact('menus', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();

        return view('admin.menu.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->aturan(), $this->pesan());

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        Menu::create($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu "' . $data['nama_menu'] . '" berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $kategori = Kategori::all();

        return view('admin.menu.edit', compact('menu', 'kategori'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate($this->aturan(), $this->pesan());

        if ($request->hasFile('gambar')) {
            $this->hapusGambarUpload($menu);
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        } else {
            unset($data['gambar']); // tidak mengganti gambar
        }

        $menu->update($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu "' . $menu->nama_menu . '" berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        // Menu yang sudah pernah dipesan tidak boleh dihapus (menjaga riwayat, NF-04)
        if (DetailPesanan::where('menu_id', $menu->id)->exists()) {
            return back()->with('error', 'Menu "' . $menu->nama_menu . '" sudah pernah dipesan, jadi tidak bisa dihapus.');
        }

        $this->hapusGambarUpload($menu);
        $menu->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    private function aturan(): array
    {
        return [
            'kategori_id' => 'required|exists:kategori,id',
            'nama_menu'   => 'required|string|max:255',
            'deskripsi'   => 'required|string|max:1000',
            'harga'       => 'required|integer|min:1000|max:10000000',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    private function pesan(): array
    {
        return [
            'kategori_id.required' => 'Pilih kategori.',
            'nama_menu.required'   => 'Nama menu wajib diisi.',
            'deskripsi.required'   => 'Deskripsi wajib diisi.',
            'harga.required'       => 'Harga wajib diisi.',
            'harga.integer'        => 'Harga harus berupa angka bulat.',
            'harga.min'            => 'Harga minimal Rp1.000.',
            'gambar.image'         => 'File harus berupa gambar.',
            'gambar.mimes'         => 'Format gambar: jpg, jpeg, png, atau webp.',
            'gambar.max'           => 'Ukuran gambar maksimal 2 MB.',
        ];
    }

    // Hanya menghapus file hasil upload, bukan gambar bawaan seeder
    private function hapusGambarUpload(Menu $menu): void
    {
        if ($menu->gambar && str_starts_with($menu->gambar, 'menu/')) {
            Storage::disk('public')->delete($menu->gambar);
        }
    }
}