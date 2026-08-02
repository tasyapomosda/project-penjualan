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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); 
            $table->string('name_merk'); // Nama jajan/ciki
            $table->string('kategori')->nullable(); // Kolomnya bernama 'kategori', sifatnya boleh kosong
            $table->integer('harga'); // Harga satuan
            $table->integer('stok_awal'); // Stok saat pertama input
            $table->integer('stok_sekarang'); // Stok yang akan berkurang saat dibeli
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
