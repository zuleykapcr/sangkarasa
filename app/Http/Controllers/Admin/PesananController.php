<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $pesanan = Pesanan::with('user')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($w) use ($request) {
                    $w->where('kode_pesanan', 'like', '%' . $request->search . '%')
                      ->orWhere('nama_penerima', 'like', '%' . $request->search . '%');
                });
            })
            ->when($status && in_array($status, Pesanan::TAHAP), fn ($q) => $q->where('status_pesanan', $status))
            ->when($status === 'belum-bayar', fn ($q) => $q->where('status_pembayaran', '!=', 'Lunas'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pesanan.index', compact('pesanan', 'status'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load('detail.menu', 'user');

        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $data = $request->validate([
            'status_pesanan'    => ['required', Rule::in(Pesanan::TAHAP)],
            'status_pembayaran' => ['required', Rule::in(['Belum dibayar', 'Lunas'])],
        ]);

        $pesanan->update($data);

        return back()->with('success', 'Status pesanan ' . $pesanan->kode_pesanan . ' diperbarui.');
    }
}