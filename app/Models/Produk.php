<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'ID_PRODUK';
    public $timestamps = false;

    protected $fillable = [
        'ID_RESEP',
        'NAMA_PRODUK',
        'KUANTITAS',
        'HARGA',
        'JENIS_PRODUK',
    ];
}
