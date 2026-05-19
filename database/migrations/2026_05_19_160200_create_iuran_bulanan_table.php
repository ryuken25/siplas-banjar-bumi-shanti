<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iuran_bulanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tagihan', 20)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan');
            $table->year('tahun');
            $table->unsignedInteger('nominal');
            $table->enum('status', ['belum_bayar', 'menunggu_verifikasi', 'lunas', 'ditolak'])
                ->default('belum_bayar')
                ->index();
            $table->enum('metode_bayar', ['transfer', 'tunai'])->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->foreignId('verifikator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'bulan', 'tahun']);
            $table->index(['tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iuran_bulanan');
    }
};
