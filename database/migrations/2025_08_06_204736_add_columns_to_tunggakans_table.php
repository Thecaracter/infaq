<?php

// File: database/migrations/2024_xx_xx_add_columns_to_tunggakans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tunggakans', function (Blueprint $table) {
            // Tambah kolom yang diperlukan untuk sistem tagihan otomatis
            $table->string('tahun_ajaran', 9)->after('bulan_tunggakan'); // Format: 2024/2025
            $table->enum('status', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar')->after('is_lunas');
            $table->integer('reminder_count')->default(0)->after('notifikasi_sent');
            $table->timestamp('last_reminder')->nullable()->after('reminder_count');
            $table->text('keterangan')->nullable()->after('last_reminder');

            // Tambah index untuk performance
            $table->index(['bulan_tunggakan', 'tahun_ajaran'], 'idx_bulan_tahun');
            $table->index(['is_lunas', 'tanggal_jatuh_tempo'], 'idx_status_tempo');
            $table->index(['siswa_id', 'bulan_tunggakan'], 'idx_siswa_bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tunggakans', function (Blueprint $table) {
            // Hapus index dulu
            $table->dropIndex('idx_bulan_tahun');
            $table->dropIndex('idx_status_tempo');
            $table->dropIndex('idx_siswa_bulan');

            // Hapus kolom
            $table->dropColumn([
                'tahun_ajaran',
                'status',
                'reminder_count',
                'last_reminder',
                'keterangan'
            ]);
        });
    }
};

?>