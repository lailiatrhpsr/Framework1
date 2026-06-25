<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    public function guest() { return view('antrian.guest'); }

    public function admin() { return view('antrian.admin'); }

    public function papan() { return view('antrian.papan'); }

    public function daftar(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'loket' => 'required|string|max:50',
        ]);

        $antrian = Antrian::create([
            'nomor_urut' => Antrian::nomorBerikutnya(),
            'nama'       => $request->nama,
            'status'     => 'menunggu',
            'loket'      => $request->loket, 
        ]);

        $posisi = Antrian::menunggu()
            ->where('loket', $request->loket)
            ->where('nomor_urut', '<=', $antrian->nomor_urut)
            ->count();

        return response()->json([
            'nomor_urut' => $antrian->nomor_urut,
            'nama'       => $antrian->nama,
            'loket'      => $antrian->loket,
            'posisi'     => $posisi,
            'estimasi'   => $posisi * 5,
        ]);
    }

    public function panggil(Request $request)
    {
        $request->validate([
            'loket' => 'required|string|max:50',
        ]);

        $antrian = Antrian::menunggu()
            ->where('loket', $request->loket)
            ->first();

        if (! $antrian) {
            return response()->json(['message' => "Tidak ada antrian di Loket {$request->loket}"], 404);
        }

        $antrian->update(['status' => 'dipanggil']);

        Cache::put('antrian_current', [
            'nomor_urut' => $antrian->nomor_urut,
            'nama'       => $antrian->nama,
            'loket'      => $antrian->loket,
            'timestamp'  => now()->timestamp,
        ], now()->addHours(8));

        return response()->json(['message' => 'OK', 'antrian' => $antrian]);
    }

    public function terlewat(Request $request, Antrian $antrian)
    {
        $antrian->update(['status' => 'terlewat']);

        return response()->json(['message' => 'Ditandai terlewat']);
    }

    public function selesai(Request $request, Antrian $antrian)
    {
        $antrian->update(['status' => 'selesai']);
        return response()->json(['message' => 'Selesai']);
    }

    public function panggilUlang(Request $request, Antrian $antrian)
    {
        $antrian->update(['status' => 'dipanggil']);

        Cache::put('antrian_current', [
            'nomor_urut' => $antrian->nomor_urut,
            'nama'       => $antrian->nama,
            'loket'      => $antrian->loket,
            'timestamp'  => now()->timestamp,
        ], now()->addHours(8));

        return response()->json(['message' => 'Dipanggil ulang', 'antrian' => $antrian]);
    }

    public function data()
    {
        return response()->json([
            'menunggu'  => Antrian::menunggu()->get(['id', 'nomor_urut', 'nama', 'loket']),
            'terlewat'  => Antrian::terlewat()->get(['id', 'nomor_urut', 'nama', 'loket']),
            'current'   => Cache::get('antrian_current'),
            'semua'     => Antrian::whereDate('created_at', today())
                               ->orderBy('nomor_urut')
                               ->get(['id', 'nomor_urut', 'nama', 'loket', 'status', 'created_at']),
            'stat'      => [
                'menunggu'  => Antrian::menunggu()->count(),
                'dipanggil' => Antrian::whereDate('updated_at', today())->where('status', 'dipanggil')->count(),
                'terlewat'  => Antrian::terlewat()->count(),
                'selesai'   => Antrian::whereDate('updated_at', today())->where('status', 'selesai')->count(),
            ],
        ]);
    }

    public function stream(Request $request)
    {
        set_time_limit(0);

        return response()->stream(function () {
            $lastTimestamp = 0;

            while (true) {
                if (connection_aborted()) {
                    break;
                }

                $current   = Cache::get('antrian_current');
                $timestamp = $current['timestamp'] ?? 0;

                $payload = [
                    'menunggu'  => Antrian::menunggu()->get(['id', 'nomor_urut', 'nama', 'loket'])->toArray(),
                    'terlewat'  => Antrian::terlewat()->get(['id', 'nomor_urut', 'nama', 'loket'])->toArray(),
                    'current'   => $current,
                    'stat'      => [
                        'menunggu'  => Antrian::menunggu()->count(),
                        'dipanggil' => Antrian::whereDate('updated_at', today())->where('status', 'dipanggil')->count(),
                        'terlewat'  => Antrian::terlewat()->count(),
                    ],
                    'new_call'  => ($timestamp > $lastTimestamp),
                ];

                if ($timestamp > $lastTimestamp) {
                    $lastTimestamp = $timestamp;
                }

                echo 'event: antrian-update' . PHP_EOL;
                echo 'data: ' . json_encode($payload) . PHP_EOL;
                echo PHP_EOL;

                ob_flush();
                flush();

                sleep(2); 
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);
    }
}