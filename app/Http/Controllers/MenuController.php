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
}
