<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengeluaran extends Model
{
    protected $table = 'detail_pengeluaran';
    protected $primaryKey = 'ID_DETAIL_PENGELUARAN';
    public $timestamps = false;

    protected $fillable = [
        'ID_BAHAN_BAKU',
        'ID_PEMBELIAN',
        'kuantitas',
        'tanggal',
    ];

    public function pembelianBahanBaku()
    {
        return $this->belongsTo(PembelianBahanBaku::class, 'ID_PEMBELIAN');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'ID_BAHAN_BAKU');
    }

    public static function getPengeluaran($startDate, $endDate)
    {
        $query = self::selectRaw('bahan_baku.NAMA_BAHAN_BAKU, detail_pengeluaran.tanggal, SUM(detail_pengeluaran.kuantitas) as total_pengeluaran')
            ->join('bahan_baku', 'detail_pengeluaran.ID_BAHAN_BAKU', '=', 'bahan_baku.ID_BAHAN_BAKU')
            ->groupBy('bahan_baku.NAMA_BAHAN_BAKU', 'detail_pengeluaran.tanggal');

        if ($startDate && $endDate) {
            $query->whereBetween('detail_pengeluaran.tanggal', [$startDate, $endDate]);
        }

        return $query->get();
    }
}
