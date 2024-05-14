<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesananProduk extends Model
{
    protected $table = 'detail_pemesanan_produk';
    protected $primaryKey = 'ID_DETAIL_PRODUK';
    public $timestamps = false;

    protected $fillable = [
        'ID_PRODUK',
        'ID_PEMESANAN',
        'KUANTITAS',
        'HARGA',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'ID_PEMESANAN');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ID_PRODUK');
    }
}
