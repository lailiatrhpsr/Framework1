<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            'Warung Sederhana',
            'Cafe Kopi Kita',
            'Bakso Mantap',
        ];

        foreach ($vendors as $nama) {
            Vendor::create([
                'nama_vendor' => $nama,
            ]);
        }
    }
}
