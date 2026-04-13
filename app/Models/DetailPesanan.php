<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'iddetail_pesanan';
    protected $fillable = ['id_menu','id_vendor', 'id_pesanan', 'jumlah', 'harga', 'subtotal', 'catatan'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }

    
}
