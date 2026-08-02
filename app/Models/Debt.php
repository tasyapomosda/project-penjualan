<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    // Pastikan semua kolom yang ada di tabel 'debts' terdaftar di sini
    protected $fillable = [
        'nama_pembeli',
        'product_id',  // Siapa yang ngutang
        'barang',
        'qty',
        'nominal',     // Berapa jumlah hutangnya
        'is_paid'        // Status lunas (0 = Belum, 1 = Lunas)
    ];

    /**
     * Relasi ke model Product
     * Ini yang bikin 'with('product')' di Controller bisa jalan
     */
    public function product()
    {
        // Debt 'belongsTo' Product (Hutang ini milik satu produk jajan)
        return $this->belongsTo(Product::class, 'product_id');
    }
}