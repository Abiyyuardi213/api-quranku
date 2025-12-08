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

        $ayat = Ayat::where('surah_id', $surah_id)
                    ->orderBy('nomor', 'asc')
                    ->get();

        return response()->json([
            'status' => true,
            'surah' => $surah->nama_latin,
            'jumlah_ayat' => $ayat->count(),
            'ayat' => $ayat
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

        $ayat = Ayat::create($request->only([
            'surah_id', 'nomor', 'ar', 'tr', 'idn'
        ]));

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

        $ayat->update($request->only(['nomor', 'ar', 'tr', 'idn']));

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

    private function cleanTransliteration($text)
    {
        $text = str_replace(['\u003C', '\u003E'], ['<', '>'], $text);

        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        $text = preg_replace('/<[^>]*>/', '', $text);

        $text = preg_replace('/\s+/', ' ', trim($text));

        return $text;
    }

    public function syncAyatSurah($nomor)
    {
        try {
            $url = "https://quran-api.santrikoding.com/api/surah/" . $nomor;
            $response = file_get_contents($url);

            if (!$response) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak dapat menghubungi API'
                ], 500);
            }

            $data = json_decode($response, true);

            if (!isset($data['nomor'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Format API tidak valid'
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

            foreach ($data['ayat'] as $a) {

                $cleanTr = $this->cleanTransliteration($a['tr']);

                Ayat::updateOrCreate(
                    ['ayat_id_api' => $a['id']],
                    [
                        'surah_id' => $surah->id,
                        'nomor'    => $a['nomor'],
                        'ar'       => $a['ar'],
                        'tr'       => $cleanTr,
                        'idn'      => $a['idn']
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
                'message' => 'Kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncSemuaSurah()
    {
        $hasil = [];

        for ($i = 1; $i <= 114; $i++) {
            $res = $this->syncAyatSurah($i);

            $hasil[] = [
                'surah'  => $i,
                'status' => $res->original['status'],
                'message'=> $res->original['message']
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Sinkronisasi surah 1–114 selesai',
            'detail' => $hasil
        ], 200);
    }
}
