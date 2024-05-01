<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengeluaran extends Model
{
    protected $table = 'detail_pengeluaran';
    protected $primaryKey = 'ID_DETAIL_PENGELUARAN';
    public $timestamps = false;

    protected $fillable = [
        'ID_BAHAN_BAKU',
        'ID_PEMBELIAN',
    ];
}
