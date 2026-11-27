<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_validasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 15)->unique();
            $table->string('nama', 100);
            $table->timestamps();
        });

        Schema::create('setting_validasi_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_validasi_id')
                ->constrained('jenis_validasis')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('urutan');
            $table->string('nama', 120);
            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->nullOnDelete();
            $table->timestamps();

            $table->unique(['jenis_validasi_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_validasi_steps');
        Schema::dropIfExists('jenis_validasis');
    }
};
