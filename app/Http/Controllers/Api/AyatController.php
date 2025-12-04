<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ayat;
use App\Models\Surah;
use Illuminate\Http\Request;

class AyatController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Ayat::orderBy('nomor','asc')->get()
        ], 200);
    }

    public function bySurah($surah_id)
    {
        $surah = Surah::find($surah_id);

        if (!$surah) {
            return response()->json([
                'status' => false,
                'message' => 'Surah tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'surah' => $surah->nama_latin,
            'ayat' => Ayat::where('surah_id', $surah_id)->orderBy('nomor')->get()
        ], 200);
    }

    public function show($id)
    {
        $ayat = Ayat::find($id);

        if (!$ayat) {
            return response()->json([
                'status' => false,
                'message' => 'Ayat tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $ayat
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'surah_id' => 'required|uuid|exists:surah,id',
            'nomor' => 'required|integer',
            'ar' => 'required|string',
            'tr' => 'required|string',
            'idn' => 'required|string'
        ]);

        $ayat = Ayat::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Ayat berhasil ditambahkan',
            'data' => $ayat
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $ayat = Ayat::find($id);

        if (!$ayat) {
            return response()->json([
                'status' => false,
                'message' => 'Ayat tidak ditemukan'
            ], 404);
        }

        $ayat->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Ayat berhasil diupdate',
            'data' => $ayat
        ], 200);
    }

    public function destroy($id)
    {
        $ayat = Ayat::find($id);

        if (!$ayat) {
            return response()->json([
                'status' => false,
                'message' => 'Ayat tidak ditemukan'
            ], 404);
        }

        $ayat->delete();

        return response()->json([
            'status' => true,
            'message' => 'Ayat berhasil dihapus'
        ], 200);
    }

    public function syncAyatSurah($nomor)
    {
        try {
            $url = "https://quran-api.santrikoding.com/api/surah/" . $nomor;

            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if (!$data || !$data['status']) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengambil data dari API'
                ], 500);
            }

            $surah = Surah::updateOrCreate(
                ['nomor' => $data['nomor']],
                [
                    'nama_arab'     => $data['nama'],
                    'nama_latin'    => $data['nama_latin'],
                    'jumlah_ayat'   => $data['jumlah_ayat'],
                    'tempat_turun'  => $data['tempat_turun'],
                    'arti'          => $data['arti'],
                    'deskripsi'     => $data['deskripsi'],
                    'audio'         => $data['audio']
                ]
            );

            foreach ($data['ayat'] as $ayatAPI) {

                Ayat::updateOrCreate(
                    ['ayat_id_api' => $ayatAPI['id']],
                    [
                        'surah_id'     => $surah->id,
                        'nomor_ayat'   => $ayatAPI['nomor'],
                        'ar'           => $ayatAPI['ar'],
                        'tr'           => $ayatAPI['tr'],
                        'idn'          => $ayatAPI['idn']
                    ]
                );
            }

            return response()->json([
                'status' => true,
                'message' => "Sinkronisasi surah {$surah->nama_latin} berhasil",
                'surah_id' => $surah->id
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
