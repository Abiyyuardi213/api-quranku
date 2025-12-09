<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hadist;
use App\Models\JenisHadist;
use Illuminate\Http\Request;

class HadistController extends Controller
{
    public function index()
    {
        $data = Hadist::with('jenis')
            ->orderBy('createdAt', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'List semua hadist',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenisId'  => 'required|integer|exists:jenishadist,id',
            'no'       => 'required|integer',
            'judul'    => 'required|string|max:191',
            'arab'     => 'required|string',
            'indo'     => 'required|string',
        ]);

        $hadist = Hadist::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Hadist berhasil ditambahkan',
            'data' => $hadist->load('jenis')
        ], 201);
    }

    public function show($id)
    {
        $hadist = Hadist::with('jenis')->find($id);

        if (!$hadist) {
            return response()->json([
                'status' => false,
                'message' => 'Hadist tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail hadist',
            'data' => $hadist
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $hadist = Hadist::find($id);

        if (!$hadist) {
            return response()->json([
                'status' => false,
                'message' => 'Hadist tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'jenisId'  => 'sometimes|required|integer|exists:jenishadist,id',
            'no'       => 'sometimes|required|integer',
            'judul'    => 'sometimes|required|string|max:191',
            'arab'     => 'sometimes|required|string',
            'indo'     => 'sometimes|required|string',
        ]);

        $hadist->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Hadist berhasil diperbarui',
            'data' => $hadist->load('jenis')
        ], 200);
    }

    public function destroy($id)
    {
        $hadist = Hadist::find($id);

        if (!$hadist) {
            return response()->json([
                'status' => false,
                'message' => 'Hadist tidak ditemukan'
            ], 404);
        }

        $hadist->delete();

        return response()->json([
            'status' => true,
            'message' => 'Hadist berhasil dihapus'
        ], 200);
    }

    public function acak()
    {
        $data = Hadist::with('jenis')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return response()->json([
            'status' => true,
            'message' => '5 hadist acak',
            'data' => $data
        ], 200);
    }
}
