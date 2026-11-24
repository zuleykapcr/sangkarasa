<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    // Simpan ulasan baru, atau perbarui ulasan lama milik user yang sama
    public function store(Request $request, Menu $menu)
    {
        $user = $request->user();

        abort_unless(
            $user->role !== 'admin' && $menu->sudahDibeliOleh($user),
            403,
            'Ulasan hanya bisa ditulis setelah pesanan menu ini selesai.'
        );

        $data = $request->validate([
            'rating'   => 'required|integer|between:1,5',
            'komentar' => 'nullable|string|max:500',
        ], [
            'rating.required' => 'Pilih jumlah bintang.',
            'rating.between'  => 'Rating harus antara 1 sampai 5.',
            'komentar.max'    => 'Komentar maksimal 500 karakter.',
        ]);

        Ulasan::updateOrCreate(
            ['user_id' => $user->id, 'menu_id' => $menu->id],
            [
                'rating'   => $data['rating'],
                'komentar' => $data['komentar'] ?? null,
                'tanggal'  => now()->toDateString(),
            ]
        );

        return redirect(route('menu.show', $menu) . '#ulasan')
            ->with('success', 'Terima kasih, ulasanmu tersimpan.');
    }

    // Pemilik ulasan atau admin boleh menghapus
    public function destroy(Request $request, Ulasan $ulasan)
    {
        $user = $request->user();

        abort_unless($user->id === $ulasan->user_id || $user->role === 'admin', 403);

        $menu = $ulasan->menu;
        $ulasan->delete();

        return redirect(route('menu.show', $menu) . '#ulasan')
            ->with('success', 'Ulasan dihapus.');
    }
}