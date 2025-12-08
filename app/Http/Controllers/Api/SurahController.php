<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ayat;
use App\Models\Surah;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SurahController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Surah::orderBy('nomor', 'asc')->get()
        ], 200);
    }

    public function show($id)
    {
        $surah = Surah::with(['ayat' => function($q){ $q->orderBy('nomor'); }])->where('nomor', $id)->first();

        if (!$surah) {
            return response()->json([
                'message' => 'Surah tidak ditemukan'
            ], 404);
        }

        $result = [
            'nomor' => (int) $surah->nomor,
            'nama' => $surah->nama_arab ?? $surah->nama ?? null,
            'jumlah_ayat' => (int) $surah->jumlah_ayat,
            'nama_latin' => $surah->nama_latin,
            'arti' => $surah->arti,
            'tempat_turun' => $surah->tempat_turun,
            'deskripsi' => $surah->deskripsi,
            'audio' => $surah->audio,
            'ayat' => $surah->ayat->map(function($a) use ($surah) {
                return [
                    'id' => $a->id,
                    'ayat_id_api' => $a->ayat_id_api ?? null,
                    'surah' => (int) $surah->nomor,
                    'nomor' => (int) $a->nomor,
                    'ar' => $a->ar,
                    'tr' => $a->tr,
                    'idn' => $a->idn
                ];
            })->values()->all()
        ];

        $next = Surah::where('nomor', '>', $surah->nomor)->orderBy('nomor')->first();
        $prev = Surah::where('nomor', '<', $surah->nomor)->orderBy('nomor', 'desc')->first();

        $result['surat_selanjutnya'] = $next ? [
            'id' => $next->id,
            'nomor' => (int)$next->nomor,
            'nama' => $next->nama_arab ?? $next->nama ?? null,
            'nama_latin' => $next->nama_latin,
            'jumlah_ayat' => (int)$next->jumlah_ayat,
            'tempat_turun' => $next->tempat_turun,
            'arti' => $next->arti,
            'deskripsi' => $next->deskripsi,
            'audio' => $next->audio
        ] : false;

        $result['surat_sebelumnya'] = $prev ? [
            'id' => $prev->id,
            'nomor' => (int)$prev->nomor,
            'nama' => $prev->nama_arab ?? $prev->nama ?? null,
            'nama_latin' => $prev->nama_latin,
            'jumlah_ayat' => (int)$prev->jumlah_ayat,
            'tempat_turun' => $prev->tempat_turun,
            'arti' => $prev->arti,
            'deskripsi' => $prev->deskripsi,
            'audio' => $prev->audio
        ] : false;

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor' => 'required|integer',
            'nama_arab' => 'required|string',
            'nama_latin' => 'required|string',
            'jumlah_ayat' => 'required|integer',
            'tempat_turun' => 'required|string',
            'arti' => 'required|string',
            'deskripsi' => 'required|string',
            'audio' => 'required|string',
        ]);

        $surah = Surah::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Surah berhasil ditambahkan',
            'data' => $surah
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $surah = Surah::find($id);

        if (!$surah) {
            return response()->json([
                'status' => false,
                'message' => 'Surah tidak ditemukan'
            ], 404);
        }

        $surah->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Surah berhasil diupdate',
            'data' => $surah
        ], 200);
    }

    public function destroy($id)
    {
        $surah = Surah::find($id);

        if (!$surah) {
            return response()->json([
                'status' => false,
                'message' => 'Surah tidak ditemukan'
            ], 404);
        }

        $surah->delete();

        return response()->json([
            'status' => true,
            'message' => 'Surah berhasil dihapus'
        ], 200);
    }

    public function syncSurahFromApi()
    {
        try {
            // URL endpoint API eksternal
            $url = "https://quran-api.santrikoding.com/api/surah";

            // Ambil data dari API eksternal
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengambil data dari API'
                ], 500);
            }

            foreach ($data as $item) {
                // lakukan insert atau update (upsert)
                Surah::updateOrCreate(
                    ['nomor' => $item['nomor']], // bagian key pencarian
                    [
                        'nama_arab'     => $item['nama'],
                        'nama_latin'    => $item['nama_latin'],
                        'jumlah_ayat'   => $item['jumlah_ayat'],
                        'tempat_turun'  => $item['tempat_turun'],
                        'arti'          => $item['arti'],
                        'deskripsi'     => $item['deskripsi'],
                        'audio'         => $item['audio']
                    ]
                );
            }

            return response()->json([
                'status' => true,
                'message' => 'Sinkronisasi berhasil dilakukan'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
