<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surah;
use Illuminate\Http\Request;

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
        $surah = Surah::find($id);

        if (!$surah) {
            return response()->json([
                'status' => false,
                'message' => 'Surah tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $surah
        ], 200);
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
