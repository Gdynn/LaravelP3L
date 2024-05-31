<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    use HasFactory;
    protected $table = 'detail_resep';
    protected $primaryKey = 'ID_DETAIL_RESEP';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'ID_BAHAN_BAKU',
        'ID_PRODUK',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ID_PRODUK');
    }

    public function bahan_baku()
    {
        return $this->belongsTo(Hampers::class, 'ID_BAHAN_BAKU');
    }
}
