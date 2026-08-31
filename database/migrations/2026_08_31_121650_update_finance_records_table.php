<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finance_records', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('id');
            $table->string('keterangan')->nullable()->after('tanggal');
            $table->enum('tipe', ['debit', 'kredit'])->nullable()->after('keterangan');
            $table->unsignedBigInteger('nominal')->default(0)->after('tipe');
        });

        Schema::table('finance_records', function (Blueprint $table) {
            $table->dropColumn(['jenis_transaksi', 'debit', 'kredit']);
        });
    }

    public function down(): void
    {
        Schema::table('finance_records', function (Blueprint $table) {
            $table->string('jenis_transaksi')->nullable();
            $table->integer('debit')->default(0);
            $table->integer('kredit')->default(0);
        });

        Schema::table('finance_records', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'keterangan', 'tipe', 'nominal']);
        });
    }
};