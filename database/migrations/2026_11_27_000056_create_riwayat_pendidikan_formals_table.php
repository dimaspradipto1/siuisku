<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_pendidikan_formals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->cascadeOnDelete();
            $table->foreignId('jenjang_pendidikan_id')
                ->constrained('jenjang_pendidikans')
                ->cascadeOnDelete();
            $table->foreignId('perguruan_tinggi_id')
                ->nullable()
                ->constrained('perguruan_tinggis')
                ->nullOnDelete();
            $table->foreignId('prodi_perguruan_tinggi_id')
                ->nullable()
                ->constrained('prodi_perguruan_tinggis')
                ->nullOnDelete();
            $table->foreignId('gelar_akademik_id')
                ->nullable()
                ->constrained('gelar_akademiks')
                ->nullOnDelete();
            $table->string('nama_institusi', 180);
            $table->string('program_studi', 150)->nullable();
            $table->unsignedSmallInteger('tahun_masuk')->nullable();
            $table->unsignedSmallInteger('tahun_lulus')->nullable();
            $table->string('no_ijazah', 100)->nullable();
            $table->date('tanggal_ijazah')->nullable();
            $table->string('file_ijazah', 255)->nullable();
            $table->decimal('ipk', 4, 2)->nullable();
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
        Schema::dropIfExists('riwayat_pendidikan_formals');
    }
};
