<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->cascadeOnDelete();
            $table->foreignId('pekerjaan_id')
                ->nullable()
                ->constrained('pekerjaans')
                ->nullOnDelete();
            $table->string('nama_instansi', 180);
            $table->string('jabatan', 150)->nullable();
            $table->string('jenis_pekerjaan', 100);
            $table->string('area_pekerjaan', 40)->default('Dalam Negeri'); // Dalam Negeri / Luar Negeri
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('file_pendukung', 255)->nullable();
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
        Schema::dropIfExists('riwayat_pekerjaans');
    }
};
