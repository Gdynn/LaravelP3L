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

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ID_PRODUK');
    }

    public function hampers()
    {
        return $this->belongsTo(Hampers::class, 'ID_HAMPERS');
    }
}
