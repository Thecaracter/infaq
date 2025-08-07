<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi_infaqs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_bayar');
            $table->string('bulan_bayar');
            $table->decimal('nominal', 10, 2);
            $table->decimal('nominal_kelas', 10, 2);
            $table->string('jenis_kelas', 20);
            $table->text('keterangan')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->boolean('notifikasi_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_infaqs');
    }
};