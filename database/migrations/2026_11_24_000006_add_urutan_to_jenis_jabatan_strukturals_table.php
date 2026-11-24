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
        Schema::table('jenis_jabatan_strukturals', function (Blueprint $table) {
            $table->unsignedInteger('urutan')->default(999)->after('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_jabatan_strukturals', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
};
