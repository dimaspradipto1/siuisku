<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_pangkats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->cascadeOnDelete();
            $table->foreignId('pangkat_id')
                ->constrained('pangkats')
                ->cascadeOnDelete();
            $table->foreignId('jenis_sk_id')
                ->nullable()
                ->constrained('jenis_sks')
                ->nullOnDelete();
            $table->date('tmt_pangkat');
            $table->string('nomor_sk', 100)->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('file_pangkat', 255)->nullable();
            $table->unsignedTinyInteger('masa_kerja_tahun')->default(0);
            $table->unsignedTinyInteger('masa_kerja_bulan')->default(0);
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
        Schema::dropIfExists('riwayat_pangkats');
    }
};
