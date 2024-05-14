<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'ID_PEMESANAN';
    public $timestamps = false;

    protected $fillable = [
        'ID_USER',
        'ID_PROMO',
        'ID_PEMBAYARAN',
        'TANGGAL_PESAN',
        'TANGGAL_LUNAS',
        'TANGGAL_AMBIL',
        'STATUS',
        'TOTAL',
        'JARAK',
        'DELIVERY',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_USER');
    }

    public function promo()
    {
        return $this->belongsTo(PromoPoin::class, 'ID_PROMO');
    }
}