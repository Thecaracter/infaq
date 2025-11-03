<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('orang_tuas', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('orang_tuas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};