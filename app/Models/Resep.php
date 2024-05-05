<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $primaryKey = 'ID_RESEP';
    public $timestamps = false;

    protected $fillable = [
        'ID_PRODUK',
        'NAMA_RESEP',
        'KUANTITAS',
    ];
}
