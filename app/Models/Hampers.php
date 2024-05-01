<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hampers extends Model
{
    protected $table = 'hampers';
    protected $primaryKey = 'ID_HAMPERS';
    public $timestamps = false;

    protected $fillable = [
        'ID_PRODUK',
        'NAMA_HAMPERS',
        'KETERANGAN',
        'HARGA',
    ];
}
