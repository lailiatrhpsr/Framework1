<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';
    protected $fillable = ['toko_id','lat_sales','lng_sales','accuracy_sales','status'];

    public function toko() {
        return $this->belongsTo(Toko::class);
    }
}

