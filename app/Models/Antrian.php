<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $fillable = [
        'nomor_urut',
        'nama',
        'status',
        'loket',
    ];

    public static function nomorBerikutnya(): int
    {
        $last = static::whereDate('created_at', today())->max('nomor_urut');
        return ($last ?? 0) + 1;
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu')->orderBy('nomor_urut');
    }

    public function scopeTerlewat($query)
    {
        return $query->where('status', 'terlewat')->orderBy('nomor_urut');
    }

    public function scopeDipanggil($query)
    {
        return $query->where('status', 'dipanggil')
                     ->whereDate('updated_at', today())
                     ->orderByDesc('updated_at');
    }
}