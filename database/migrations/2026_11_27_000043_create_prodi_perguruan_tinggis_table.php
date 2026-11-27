<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodi_perguruan_tinggis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perguruan_tinggi_id')
                ->constrained('perguruan_tinggis')
                ->cascadeOnDelete();
            $table->foreignId('jenjang_pendidikan_id')
                ->nullable()
                ->constrained('jenjang_pendidikans')
                ->nullOnDelete();
            $table->string('kode', 20);
            $table->string('nama', 180);
            $table->string('alamat', 255)->nullable();
            $table->string('no_telp', 30)->nullable();
            $table->timestamps();

            $table->unique(['perguruan_tinggi_id', 'kode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodi_perguruan_tinggis');
    }
};
