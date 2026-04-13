<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Vendor;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Warung Sederhana' => [
                ['nama_menu' => 'Nasi Goreng Spesial', 'harga' => 20000, 'path_gambar' => '/img/nasigoreng.jpg'],
                ['nama_menu' => 'Ayam Bakar', 'harga' => 25000, 'path_gambar' => '/img/ayambakar.jpg'],
                ['nama_menu' => 'Es Teh Manis', 'harga' => 5000, 'path_gambar' => '/img/esteh.jpg'],
                ['nama_menu' => 'Jus Alpukat', 'harga' => 15000, 'path_gambar' => '/img/jusalpukat.jpg'],
            ],
            'Cafe Kopi Kita' => [
                ['nama_menu' => 'Kopi Hitam', 'harga' => 10000, 'path_gambar' => '/img/kopi.jpg'],
                ['nama_menu' => 'Cappuccino', 'harga' => 18000, 'path_gambar' => '/img/cappuccino.jpg'],
                ['nama_menu' => 'Roti Bakar', 'harga' => 12000, 'path_gambar' => '/img/rotibakar.jpg'],
                ['nama_menu' => 'Es Kopi Susu', 'harga' => 15000, 'path_gambar' => '/img/eskopisusu.jpg'],
            ],
            'Bakso Mantap' => [
                ['nama_menu' => 'Bakso Urat', 'harga' => 22000, 'path_gambar' => '/img/baksourat.jpg'],
                ['nama_menu' => 'Bakso Telur', 'harga' => 25000, 'path_gambar' => '/img/baksotelur.jpg'],
                ['nama_menu' => 'Es Jeruk', 'harga' => 8000, 'path_gambar' => '/img/esjeruk.jpg'],
                ['nama_menu' => 'Mie Ayam', 'harga' => 20000, 'path_gambar' => '/img/mieayam.jpg'],
            ],
        ];

        foreach ($data as $vendorName => $menus) {
            $vendor = Vendor::where('nama_vendor', $vendorName)->first();
            foreach ($menus as $menu) {
                Menu::create([
                    'id_vendor'   => $vendor->id_vendor,
                    'nama_menu'   => $menu['nama_menu'],
                    'harga'       => $menu['harga'],
                    'path_gambar' => $menu['path_gambar'],
                ]);
            }
        }
    }
}
