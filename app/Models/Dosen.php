<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;
    protected $fillable = ['nip', 'nama'];
    public function absensis() {
        return $this->hasMany(Absensi::class, 'dosen_id');
    }
}
