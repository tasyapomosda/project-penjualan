<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasManual extends Model
{
    // Tambahkan ini jika nama tabel di database kamu adalah 'kas_manual'
    protected $table = 'kas_manuals';

    protected $fillable = ['keterangan', 'tipe', 'nominal', 'tanggal'];
}