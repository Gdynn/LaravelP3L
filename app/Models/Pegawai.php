<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeUnit\FunctionUnit;


class Pegawai extends Model
{
    protected $table = 'pegawai';

    use HasFactory;
    protected $primaryKey = 'ID_PEGAWAI';
    public $timestamps = false;

    protected $fillable = [
        'ALAMAT',
        'EMAIL',
        'NAMA_PEGAWAI',
        'NOTELP_PEGAWAI',
        'ID_JABATAN',
        'ID_PRESENSI',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'ID_JABATAN');
    }

    public function presensi()
    {
        return $this->belongsTo(Presensi::class, 'ID_PRESENSI');
    }
}
