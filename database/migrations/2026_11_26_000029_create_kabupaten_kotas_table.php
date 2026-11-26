<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kabupaten_kotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provinsi_id')
                ->constrained('provinsis')
                ->cascadeOnDelete();
            $table->string('kode', 10)->unique();
            $table->string('nama', 150);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kabupaten_kotas');
    }
};
