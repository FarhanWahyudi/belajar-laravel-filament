<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'nama_customer',
        'kode_customer',
        'alamat_customer',
        'telepon_customer',
    ];

    public function fakturs(): HasMany
    {
        return $this->hasMany(Faktur::class);
    }

    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class);
    }
}
