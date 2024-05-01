<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    protected $table = 'saldo';
    protected $primaryKey = 'ID_SALDO';
    public $timestamps = false;

    protected $fillable = [
        'ID_USER',
        'JUMLAH',
        'POIN',
    ];
}
