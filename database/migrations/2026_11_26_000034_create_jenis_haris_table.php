<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_haris', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('kode')->unique();
            $table->string('nama', 30);
            $table->string('jenis_hari', 20)->default('Efektif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_haris');
    }
};
