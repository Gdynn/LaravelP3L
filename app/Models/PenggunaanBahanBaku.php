<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaanBahanBaku extends Model
{
    use HasFactory;
    protected $table = 'penggunaan_bahan_baku';
    protected $primaryKey = 'ID_PENGGUNAAN';
    public $timestamps = false;

    protected $fillable = [
        'ID_BAHAN_BAKU',
        'TANGGAL',
        'KUANTITAS',
    ];

    public function bahanbaku()
    {
        return $this->belongsTo(BahanBaku::class, 'ID_BAHAN_BAKU', 'ID_BAHAN_BAKU');
    }
}
