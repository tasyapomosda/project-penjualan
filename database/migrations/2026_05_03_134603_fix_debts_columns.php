public function up()
{
    Schema::table('debts', function (Blueprint $table) {
        // Tambahkan hanya kolom yang belum ada
        if (!Schema::hasColumn('debts', 'product_id')) {
            $table->unsignedBigInteger('product_id')->nullable()->after('nama_pembeli');
        }
        if (!Schema::hasColumn('debts', 'barang')) {
            $table->string('barang')->nullable()->after('product_id');
        }
        if (!Schema::hasColumn('debts', 'qty')) {
            $table->integer('qty')->default(1)->after('barang');
        }
    });
}

public function down()
{
    Schema::table('debts', function (Blueprint $table) {
        $table->dropColumn(['product_id', 'barang', 'qty']);
    });
}