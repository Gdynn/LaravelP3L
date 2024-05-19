<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;

    protected $table = 'alamat';
    protected $primaryKey = 'ID_ALAMAT';
    public $timestamps = false;

    protected $fillable = [
        'ID_USER',
        'NAMA_ALAMAT',
        'ALAMAT',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_USER', 'id_user');
    }
}
