<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Pastikan ini sesuai dengan lokasi model User Anda


class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'ID_PEMESANAN';
    public $timestamps = false;

    protected $fillable = [
        'ID_USER',
        'TANGGAL_PESAN',
        'TANGGAL_LUNAS',
        'TANGGAL_AMBIL',
        'STATUS',
        'TOTAL',
        'BUKTI_BAYAR',
        'JUMLAH_BAYAR',
        'JARAK',
        'DELIVERY',
        'ALAMAT',
        'TIP',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_USER');
    }

    public function promo()
    {
        return $this->belongsTo(PromoPoin::class, 'ID_PROMO');
    }
    protected $casts = [
        'BUKTI_BAYAR' => 'array',
        'ID_PEMESANAN' => 'string',
    ];
    public function getBuktiBayarAttribute($value)
    {
        return $value ? base64_encode($value) : null;
    }
}
