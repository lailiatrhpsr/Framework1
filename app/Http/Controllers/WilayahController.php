<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index() {
        return view('admin.wilayah'); 
    }

    private function csvToJson($filePath) {
        $rows = array_map(function($line) {
            return str_getcsv($line, ';'); 
        }, file($filePath));

        $header = array_shift($rows);
        $json = [];
        foreach ($rows as $row) {
            if (count($row) == count($header)) {
                $json[] = array_combine($header, $row);
            }
        }
        return $json;
    }

    public function provinsi() {
        $data = $this->csvToJson(resource_path('data/provinces.csv'));
        return response()->json($data);
    }

    public function kota($provinsi_id) {
        $data = $this->csvToJson(resource_path('data/regencies.csv'));
        $filtered = array_filter($data, fn($item) => $item['province_id'] == $provinsi_id);
        return response()->json(array_values($filtered));
    }

    public function kecamatan($kota_id) {
        $data = $this->csvToJson(resource_path('data/districts.csv'));
        $filtered = array_filter($data, fn($item) => $item['regency_id'] == $kota_id);
        return response()->json(array_values($filtered));
    }

    public function kelurahan($kecamatan_id) {
        $data = $this->csvToJson(resource_path('data/villages.csv'));
        $filtered = array_filter($data, fn($item) => $item['district_id'] == $kecamatan_id);
        return response()->json(array_values($filtered));
    }
}
