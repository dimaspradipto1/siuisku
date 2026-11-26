<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jabatan_strukturals', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->string('nama', 150);
            $table->foreignId('jenis_jabatan_struktural_id')->nullable()->constrained('jenis_jabatan_strukturals')->nullOnDelete();
            $table->string('singkatan', 50)->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('jabatan_strukturals')->nullOnDelete();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->nullOnDelete();
            $table->string('email', 150)->nullable();
            $table->string('eselon', 30)->nullable();
            $table->string('pangkat_min', 50)->nullable();
            $table->string('pangkat_max', 50)->nullable();
            $table->decimal('beban_sks', 5, 2)->nullable();
            $table->boolean('is_pimpinan')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->unsignedInteger('urutan')->default(999);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_strukturals');
    }
};
