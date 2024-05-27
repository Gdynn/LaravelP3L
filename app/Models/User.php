<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Passport\HasApiTokens;

use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'id_saldo',
        'username',
        'email',
        'password',
        'notelp',
        'saldo',
        'poin',
        'tanggal_lahir',
        'type_pengguna',
        'verify_key',
        'active',
    ];

    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'id_saldo', 'id_saldo');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'ID_USER', 'id_user');
    }

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'ID_USER', 'id_user'); // Changed from hasOne to hasMany
    }
}

