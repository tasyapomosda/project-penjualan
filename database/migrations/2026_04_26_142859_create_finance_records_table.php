<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk MEMBANGUN tabel.
     */
    public function up(): void
    {
        Schema::create('finance_records', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_transaksi'); // Kolom utama yang tadi error
            $table->integer('debit')->default(0);
            $table->integer('kredit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Jalankan migrasi untuk MENGHAPUS tabel (Rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_records');
    }
};