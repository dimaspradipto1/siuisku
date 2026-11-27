<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            if (! Schema::hasColumn('pegawais', 'klasifikasi_sdm_id')) {
                $table->foreignId('klasifikasi_sdm_id')
                    ->nullable()
                    ->after('jabatan_fungsional_id')
                    ->constrained('klasifikasi_sdms')
                    ->nullOnDelete();
            }
        });

        Schema::create('keluarga_pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->cascadeOnDelete();
            $table->string('hubungan', 20)->default('pasangan'); // pasangan, anak, orang_tua
            $table->string('nama', 150);
            $table->string('jenis_kelamin', 1)->nullable(); // L / P
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('kartu_nikah', 80)->nullable();
            $table->string('status_hubungan', 60)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->date('tanggal_diajukan')->nullable();
            $table->string('status', 20)->default('diajukan'); // draft, diajukan, disetujui, ditolak
            $table->text('catatan_validasi')->nullable();
            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            $table->index(['hubungan', 'status']);
            $table->index(['pegawai_id', 'hubungan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluarga_pegawais');

        Schema::table('pegawais', function (Blueprint $table) {
            if (Schema::hasColumn('pegawais', 'klasifikasi_sdm_id')) {
                $table->dropConstrainedForeignId('klasifikasi_sdm_id');
            }
        });
    }
};
