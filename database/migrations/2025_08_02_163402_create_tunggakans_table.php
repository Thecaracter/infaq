<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tunggakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('bulan_tunggakan');
            $table->decimal('nominal', 10, 2);
            $table->decimal('nominal_kelas', 10, 2);
            $table->string('jenis_kelas', 20);
            $table->boolean('is_lunas')->default(false);
            $table->date('tanggal_jatuh_tempo');
            $table->boolean('notifikasi_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tunggakans');
    }
};