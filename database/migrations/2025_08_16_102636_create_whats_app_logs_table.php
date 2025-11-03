<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('whats_app_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('phone_number');
            $table->text('message');
            $table->enum('message_type', ['reminder', 'payment_confirmation', 'custom'])->default('custom');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->json('response_data')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['message_type', 'created_at']);
            $table->index(['siswa_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('whats_app_logs');
    }
};