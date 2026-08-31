<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceRecord extends Model
{
    protected $fillable = [
    'tanggal', 
    'keterangan', 
    'tipe',
    'nominal'
    ];
}
