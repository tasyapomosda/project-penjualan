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
        Schema::create('debts', function (Blueprint $table) {
        $table->id();
        $table->string('nama_pembeli');
        $table->string('barang'); // Jajanan yang diambil
        $table->integer('qty');
        $table->integer('nominal'); // Harga total (qty * harga_satuan)
        $table->boolean('is_paid')->default(false); // Biar bisa ditandai kalau sudah lunas
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
