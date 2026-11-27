<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_diklats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->cascadeOnDelete();
            $table->string('nama_diklat', 200);
            $table->string('jenis_diklat', 80);
            $table->string('penyelenggara', 200);
            $table->unsignedSmallInteger('tahun');
            $table->unsignedSmallInteger('jumlah_jam')->nullable();
            $table->string('nomor_sertifikat', 100)->nullable();
            $table->date('tanggal_sertifikat')->nullable();
            $table->string('file_sertifikat', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal_diajukan')->nullable();
            $table->string('status', 20)->default('diajukan'); // draft, diajukan, disetujui, ditolak, ditangguhkan
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
        Schema::dropIfExists('riwayat_diklats');
    }
};
