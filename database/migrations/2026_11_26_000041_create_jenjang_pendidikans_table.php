<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenjang_pendidikans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 15)->unique();
            $table->string('nama', 150);
            $table->string('nama_en', 150)->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('is_perguruan_tinggi')->default(false);
            $table->boolean('is_pasca_sarjana')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenjang_pendidikans');
    }
};
