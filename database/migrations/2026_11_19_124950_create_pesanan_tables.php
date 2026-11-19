<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->nullable()->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('nama_penerima');
            $table->string('alamat_pengiriman');
            $table->string('kota');
            $table->string('kode_pos');
            $table->date('tanggal_pengiriman');
            $table->string('waktu_pengiriman');
            $table->text('catatan_pesanan')->nullable();
            $table->string('metode_pembayaran');
            $table->decimal('ongkos_kirim', 12, 0)->default(0);
            $table->decimal('potongan_diskon', 12, 0)->default(0);
            $table->decimal('total_biaya', 12, 0);
            $table->string('status_pembayaran')->default('Belum dibayar');
            $table->string('status_pesanan')->default('Menunggu');
            $table->timestamps();
        });

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menu')->restrictOnDelete();
            $table->string('opsi_tambahan')->nullable();
            $table->unsignedInteger('jumlah_porsi');
            $table->decimal('subtotal', 12, 0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
    }
};