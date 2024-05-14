<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoPoin extends Model
{
    protected $table = 'promo';
    protected $primaryKey = 'ID_PROMO';
    public $timestamps = false;

    protected $fillable = [
        'NAMA_PROMO',
        'POIN',
        'HARGA_PEMESANAN',
    ];
}
