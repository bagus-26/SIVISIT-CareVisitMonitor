<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel log aktivitas sistem (otomatis dicatat)
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('action', 100);       // CREATE, READ, UPDATE, DELETE, LOGIN, LOGOUT
            $table->string('module', 100);        // patients, monitorings, rekam-medis, auth
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        // Tabel AI Usage Log (diisi manual sesuai format SRS)
        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_anggota');
            $table->string('tools_ai');           // ChatGPT, GitHub Copilot, Gemini, dll
            $table->text('prompt_penting');
            $table->text('hasil_dari_ai');
            $table->text('verifikasi_revisi_tim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usage_logs');
        Schema::dropIfExists('usage_logs');
    }
};
