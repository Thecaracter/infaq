<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_wali');
            $table->string('no_hp');
            $table->text('alamat');
            $table->string('pekerjaan')->nullable();
            $table->enum('hubungan', ['ayah', 'ibu', 'wali'])->default('ayah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orang_tuas');
    }
};