<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'nama_pembeli', 
        'jumlah', 
        'total_harga', 
        'metode_bayar',
        'nama_produk_manual',
        'created_at',
    ];

    // INI KUNCI AGAR NAMA PRODUK MUNCUL
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}