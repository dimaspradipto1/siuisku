<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_hubungan_kerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->cascadeOnDelete();
            $table->foreignId('hubungan_kerja_id')
                ->constrained('hubungan_kerjas')
                ->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('nomor_sk', 100)->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('file_hubungan_kerja', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal_diajukan')->nullable();
            $table->string('status', 20)->default('diajukan'); // draft, diajukan, disetujui, ditolak
            $table->text('catatan_validasi')->nullable();
            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['pegawai_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_hubungan_kerjas');
    }
};
