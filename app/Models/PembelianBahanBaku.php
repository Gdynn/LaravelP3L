<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBahanBaku extends Model
{
    protected $table = 'pembelian_bahan_baku';
    protected $primaryKey = 'ID_PEMBELIAN';
    public $timestamps = false;

    protected $fillable = [
        'KUANTITAS',
        'HARGA',
        'TANGGAL',
    ];
}
