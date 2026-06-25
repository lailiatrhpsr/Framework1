<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Absensi;
use App\Models\Dosen;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $listDosen = Dosen::all();
        return view('absensi.scanner', compact('listDosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nfc_serial' => 'required|string',
            'dosen_id' => 'required|integer'
        ]);

        $mahasiswa = Mahasiswa::where('nfc_serial', $request->nfc_serial)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu NFC tidak terdaftar! UID: ' . $request->nfc_serial
            ], 404);
        }

        $sudahAbsen = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->where('dosen_id', $request->dosen_id)
            ->whereDate('waktu_hadir', Carbon::today())
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => $mahasiswa->nama . ' sudah melakukan absensi hari ini!'
            ], 400);
        }

        $absensi = new Absensi();
        $absensi->mahasiswa_id = $mahasiswa->id;
        $absensi->dosen_id = $request->dosen_id;
        $absensi->waktu_hadir = Carbon::now();
        $absensi->status = 'Hadir';
        $absensi->save();

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil dicatat!',
            'data' => [
                'nama' => $mahasiswa->nama,
                'nim' => $mahasiswa->nim,
                'waktu' => Carbon::parse($absensi->waktu_hadir)->format('H:i:s')
            ]
        ], 200);
    }
}