<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailHampers extends Model
{
    protected $table = 'detail_hampers';
    protected $primaryKey = 'ID_DETAIL_HAMPERS';
    public $timestamps = false;

    protected $fillable = [
        'ID_HAMPERS',
        'ID_PRODUK',
    ];
}
