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
        Schema::create('kas_manuals', function (Blueprint $table) {
        $table->id();
        $table->string('keterangan');
        $table->enum('tipe', ['debit', 'kredit']);
        $table->bigInteger('nominal'); // <--- PASTIKAN BARIS INI ADA
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_manuals');
    }
};
