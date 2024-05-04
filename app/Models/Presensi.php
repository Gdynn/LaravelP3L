<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    protected $primaryKey = 'ID_PRESENSI';
    public $timestamps = false;

    protected $fillable = [
        'TANGGAL',
        'STATUS',
    ];
}
