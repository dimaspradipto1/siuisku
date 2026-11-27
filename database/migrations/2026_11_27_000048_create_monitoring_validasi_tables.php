<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('nip', 30)->unique();
            $table->string('nidn', 30)->nullable();
            $table->string('nama', 150);
            $table->foreignId('unit_kerja_id')
                ->nullable()
                ->constrained('unit_kerjas')
                ->nullOnDelete();
            $table->foreignId('status_aktif_id')
                ->nullable()
                ->constrained('status_aktifs')
                ->nullOnDelete();
            $table->foreignId('hubungan_kerja_id')
                ->nullable()
                ->constrained('hubungan_kerjas')
                ->nullOnDelete();
            $table->foreignId('jabatan_fungsional_id')
                ->nullable()
                ->constrained('jabatan_fungsionals')
                ->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('jenis_riwayats', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 40)->unique();
            $table->string('kategori', 60);
            $table->string('nama', 100);
            $table->unsignedTinyInteger('urutan_kategori')->default(1);
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->timestamps();
        });

        Schema::create('riwayat_pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->cascadeOnDelete();
            $table->foreignId('jenis_riwayat_id')
                ->constrained('jenis_riwayats')
                ->cascadeOnDelete();
            $table->string('judul', 180);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->string('status', 20)->default('draft');
            $table->text('catatan_validasi')->nullable();
            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            $table->index(['jenis_riwayat_id', 'status']);
            $table->index(['pegawai_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_pegawais');
        Schema::dropIfExists('jenis_riwayats');
        Schema::dropIfExists('pegawais');
    }
};
