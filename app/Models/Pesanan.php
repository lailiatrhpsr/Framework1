<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $fillable = ['nama', 'total', 'metode_bayar', 'status'];
    public $timestamps = false;

    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan');
    }
}
