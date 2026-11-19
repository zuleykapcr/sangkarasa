<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Support\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = Keranjang::items();

        if ($items->isEmpty()) {
            return redirect()->route('menu.index')
                ->with('error', 'Keranjangmu masih kosong. Pilih menu dulu, yuk.');
        }

        $ringkasan = Keranjang::ringkasan($items, session('voucher'));
        $jadwal    = session('jadwal_default', []);
        $kota      = config('sangkarasa.kota');
        $waktu     = config('sangkarasa.waktu_tiba');

        return view('checkout.index', compact('items', 'ringkasan', 'jadwal', 'kota', 'waktu'));
    }

    // Tombol "Pakai" voucher (form yang sama dengan checkout, isian tetap terbawa)
    public function pakaiVoucher(Request $request)
    {
        $kode     = strtoupper(trim((string) $request->input('kode_voucher')));
        $items    = Keranjang::items();
        $subtotal = (int) $items->sum('subtotal');
        $voucher  = $kode !== '' ? config('sangkarasa.vouchers.' . $kode) : null;
        $input    = $request->except('_token', 'kode_voucher');

        if (!$voucher) {
            return back()->withInput($input)->with('error', 'Kode voucher tidak ditemukan.');
        }

        if ($subtotal < $voucher['min_belanja']) {
            return back()->withInput($input)->with(
                'error',
                'Voucher ini berlaku untuk belanja minimal Rp' . number_format($voucher['min_belanja'], 0, ',', '.') . '.'
            );
        }

        session(['voucher' => $kode]);

        return back()->withInput($input)->with(
            'success',
            'Hore, kamu hemat Rp' . number_format($voucher['potongan'], 0, ',', '.') . '!'
        );
    }

    public function hapusVoucher(Request $request)
    {
        session()->forget('voucher');

        return back()->withInput($request->except('_token', 'kode_voucher'))
            ->with('success', 'Voucher dilepas.');
    }

    // Simpan pesanan ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_penerima'      => 'required|string|max:255',
            'alamat_pengiriman'  => 'required|string|max:255',
            'kota'               => ['required', Rule::in(config('sangkarasa.kota'))],
            'kode_pos'           => 'required|digits:5',
            'tanggal_pengiriman' => 'required|date|after_or_equal:tomorrow',
            'waktu_pengiriman'   => ['required', Rule::in(config('sangkarasa.waktu_tiba'))],
            'catatan_pesanan'    => 'nullable|string|max:500',
            'metode_pembayaran'  => ['required', Rule::in(['QRIS', 'Transfer Bank'])],
        ], [
            'nama_penerima.required'      => 'Nama penerima wajib diisi.',
            'alamat_pengiriman.required'  => 'Alamat lengkap wajib diisi.',
            'kota.required'               => 'Pilih kota atau kabupaten.',
            'kode_pos.required'           => 'Kode pos wajib diisi.',
            'kode_pos.digits'             => 'Kode pos harus 5 angka.',
            'tanggal_pengiriman.required' => 'Tanggal pengiriman wajib diisi.',
            'tanggal_pengiriman.after_or_equal' => 'Pesanan minimal H-1 sebelum hari pengiriman.',
            'metode_pembayaran.required'  => 'Pilih metode pembayaran.',
        ]);

        $items = Keranjang::items();

        if ($items->isEmpty()) {
            return redirect()->route('menu.index')->with('error', 'Keranjangmu kosong.');
        }

        $ringkasan = Keranjang::ringkasan($items, session('voucher'));

        $pesanan = DB::transaction(function () use ($request, $items, $ringkasan) {
            $pesanan = Pesanan::create([
                'user_id'            => auth()->id(),
                'nama_penerima'      => $request->nama_penerima,
                'alamat_pengiriman'  => $request->alamat_pengiriman,
                'kota'               => $request->kota,
                'kode_pos'           => $request->kode_pos,
                'tanggal_pengiriman' => $request->tanggal_pengiriman,
                'waktu_pengiriman'   => $request->waktu_pengiriman,
                'catatan_pesanan'    => $request->catatan_pesanan,
                'metode_pembayaran'  => $request->metode_pembayaran,
                'ongkos_kirim'       => $ringkasan['ongkir'],
                'potongan_diskon'    => $ringkasan['potongan'],
                'total_biaya'        => $ringkasan['total'],
                'status_pembayaran'  => 'Belum dibayar',
                'status_pesanan'     => 'Menunggu',
            ]);

            // Contoh kode: SR-261119-007
            $pesanan->update([
                'kode_pesanan' => 'SR-' . now()->format('ymd') . '-' . str_pad($pesanan->id, 3, '0', STR_PAD_LEFT),
            ]);

            foreach ($items as $item) {
                $pesanan->detail()->create([
                    'menu_id'       => $item['menu']->id,
                    'opsi_tambahan' => $item['opsi'],
                    'jumlah_porsi'  => $item['porsi'],
                    'subtotal'      => $item['subtotal'],
                ]);
            }

            return $pesanan;
        });

        session()->forget(['keranjang', 'voucher', 'jadwal_default']);

        return redirect()->route('pesanan.pembayaran', $pesanan)
            ->with('success', 'Pesanan berhasil dibuat. Selesaikan pembayaran, ya.');
    }

    // Halaman instruksi pembayaran
    public function pembayaran(Pesanan $pesanan)
    {
        abort_unless($pesanan->user_id === auth()->id(), 403);

        $pesanan->load('detail.menu');

        return view('checkout.pembayaran', compact('pesanan'));
    }
}