<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Pastikan 'kategori' ada di daftar ini
    protected $fillable = [
    'name_merk', 
    'kategori', 
    'harga',
    'stok_awal',
    'stok_sekarang', 
    ];
}