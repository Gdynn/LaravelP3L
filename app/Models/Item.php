<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'ITEM';
    protected $primaryKey = 'id_item';
    public $timestamps = false;

    protected $fillable = [
        'nama_item',
        'harga',
        'deskripsi'
    ];
}
