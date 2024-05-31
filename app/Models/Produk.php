<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $primaryKey = 'ID_PRODUK';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'NAMA_PRODUK',
        'KUANTITAS',
        'HARGA',
        'JENIS_PRODUK',
    ];

    public function limitHarian()
    {
        return $this->hasMany(LimitHarian::class, 'ID_PRODUK', 'ID_PRODUK');
    }
}
