<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Pastikan ini sesuai dengan lokasi model User Anda

class Pemesanan extends Model
{
    use HasFactory;
    protected $table = 'pemesanan';
    protected $primaryKey = 'ID_PEMESANAN';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ID_PEMESANAN',
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
        'fcm',
        'jumlah'
    ];

    protected $casts = [
        'ID_PEMESANAN' => 'string',
        'TANGGAL_PESAN' => 'datetime',
        'TANGGAL_LUNAS' => 'datetime',
        'TANGGAL_AMBIL' => 'datetime',
        'BUKTI_BAYAR' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_USER');
    }

    public function setBuktiBayarAttribute($value)
    {
        $this->attributes['BUKTI_BAYAR'] = base64_encode($value);
    }
    public function getBuktiBayarAttribute($value)
    {
        return $value ? base64_encode($value) : null;
    }

    public function promo()
    {
        return $this->belongsTo(PromoPoin::class, 'ID_PROMO', 'ID_PROMO');
    }

    public function detailPemesananProduk()
    {
        return $this->hasMany(DetailPemesananProduk::class, 'ID_PEMESANAN', 'ID_PEMESANAN');
    }

    // Define the relationship with DetailPemesananHampers
    public function detailPemesananHampers()
    {
        return $this->hasMany(DetailPemesananHampers::class, 'ID_PEMESANAN', 'ID_PEMESANAN');
    }

    public static function getMonthlySales()
    {
        $statuses = ['Selesai', 'Siap di pick up', 'Diambil sendiri', 'Diproses'];

        $sales = self::selectRaw('YEAR(TANGGAL_PESAN) as year, MONTH(TANGGAL_PESAN) as month, DAY(TANGGAL_PESAN) as day, SUM(jumlah) as total_sales')
            ->whereIn('status', $statuses)
            ->groupBy('year', 'month', 'day')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->orderBy('day', 'asc')
            ->get();

        Log::info('Monthly Sales Data: ', $sales->toArray());

        return $sales;
    }
}
