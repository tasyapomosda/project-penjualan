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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        // Ini menghubungkan transaksi ke produk (Foreign Key)
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        $table->string('nama_pembeli');
        $table->integer('jumlah');
        $table->integer('total_harga');
        // Pilihan metode bayar sesuai rencana awalmu
        $table->enum('metode_bayar', ['cash', 'qris', 'hutang']);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
