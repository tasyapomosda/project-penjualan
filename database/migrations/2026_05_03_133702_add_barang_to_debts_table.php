<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->string('barang')->nullable()->after('product_id');
            // atau jika sudah ada tapi NOT NULL, ubah jadi nullable:
            // $table->string('barang')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->dropColumn('barang');
        });
    }
};
