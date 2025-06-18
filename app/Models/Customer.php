<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'nama_customer',
        'kode_customer',
        'alamat_customer',
        'telepon_customer',
    ];
}
