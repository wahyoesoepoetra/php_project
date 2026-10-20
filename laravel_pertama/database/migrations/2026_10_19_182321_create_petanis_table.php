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
        Schema::create('petanis', function (Blueprint $table) {
        $table->id('id_penjual');
        $table->foreignId('id_kelompok_tani');
        $table->string('nama');
        $table->string('nik', 16);
        $table->string('alamat', 100);
        $table->string('telp');
        $table->string('foto', 100);
        $table->char('status');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petanis');
    }
};
