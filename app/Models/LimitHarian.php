<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimitHarian extends Model
{
    use HasFactory;
    protected $table = 'limit_harian';
    protected $primaryKey = 'ID_LIMIT';
    public $timestamps = false;

    protected $fillable = [
        'ID_PRODUK',
        'TANGGAL',
        'LIMIT_KUANTITAS',
        'STOK_HARI_INI',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ID_PRODUK', 'ID_PRODUK');
    }
}
