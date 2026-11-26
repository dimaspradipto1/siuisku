<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rumpun_bidang_ilmus', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 180);
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('rumpun_bidang_ilmus')
                ->nullOnDelete();
            $table->unsignedTinyInteger('level')->default(0);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rumpun_bidang_ilmus');
    }
};
