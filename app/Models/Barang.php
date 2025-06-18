<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $fillable = [
        'nama_barang',
        'harga_barang',
        'kode_barang',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(Detail::class);
    }
}
