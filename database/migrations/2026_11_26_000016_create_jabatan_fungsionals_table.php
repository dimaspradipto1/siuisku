<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatan_fungsionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('klasifikasi_sdm_id')
                ->constrained('klasifikasi_sdms')
                ->cascadeOnDelete();
            $table->string('kode', 20);
            $table->string('nama', 150);
            $table->foreignId('pangkat_id')
                ->nullable()
                ->constrained('pangkats')
                ->nullOnDelete();
            $table->unsignedInteger('angka_kredit')->default(0);
            $table->unsignedSmallInteger('usia_pensiun')->default(65);
            $table->string('referensi_sister', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['klasifikasi_sdm_id', 'kode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatan_fungsionals');
    }
};
