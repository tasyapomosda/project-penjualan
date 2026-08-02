<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceRecord extends Model
{
    protected $fillable = [
    'jenis_transaksi', 
    'debit', 
    'kredit',
    ];
}
