<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_sampah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laporan', 20)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('jenis_sampah', ['organik', 'anorganik', 'b3', 'campuran']);
            $table->text('lokasi_text');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('keterangan');
            $table->string('foto');
            $table->enum('status', ['dikirim', 'diterima', 'diproses', 'selesai', 'ditolak'])
                ->default('dikirim')
                ->index();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('alasan_tolak')->nullable();
            $table->timestamp('tanggal_lapor');
            $table->timestamp('tanggal_diterima')->nullable();
            $table->timestamp('tanggal_diproses')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_sampah');
    }
};
