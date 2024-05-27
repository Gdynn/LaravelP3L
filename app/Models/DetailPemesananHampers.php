<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesananHampers extends Model
{
    use HasFactory;
    protected $table = 'detail_pemesanan_hampers';
    protected $primaryKey = 'ID_DETAIL_HAMPERS';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'ID_HAMPERS',
        'ID_PEMESANAN',
        'KUANTITAS',
        'HARGA',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'ID_PEMESANAN', 'ID_PEMESANAN');
    }

    public function hampers()
    {
        return $this->belongsTo(Hampers::class, 'ID_HAMPERS', 'ID_HAMPERS');
    }
}
