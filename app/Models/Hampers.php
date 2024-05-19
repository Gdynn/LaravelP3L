<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hampers extends Model
{
    use HasFactory;
    protected $table = 'hampers';
    protected $primaryKey = 'ID_HAMPERS';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'NAMA_HAMPERS',
        'KETERANGAN',
        'HARGA',
    ];
}
