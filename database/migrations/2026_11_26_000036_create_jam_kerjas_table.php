<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jam_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->boolean('is_normal')->default(false);
            $table->string('jam_datang', 5);
            $table->string('jam_pulang', 5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jam_kerjas');
    }
};
