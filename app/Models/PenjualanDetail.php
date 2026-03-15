<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_detail'; // 
    protected $primaryKey = 'idpenjualan_detail';
    public $timestamps = false; 

    protected $fillable = [
        'id_penjualan', 
        'id_barang', 
        'jumlah', 
        'subtotal'
    ];
}
