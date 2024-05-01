<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';
    protected $primaryKey = 'ID_BAHAN_BAKU';
    public $timestamps = false;

    protected $fillable = [
        'NAMA_BAHAN_BAKU',
        'STOK',
    ];
}
