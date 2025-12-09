<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisHadist;
use Illuminate\Http\Request;

class JenisHadistController extends Controller
{
    public function index()
    {
        $data = JenisHadist::orderBy('nama', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'List semua jenis hadist',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:191|unique:jenishadist,nama',
        ]);

        $jenis = JenisHadist::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Jenis hadist berhasil ditambahkan',
            'data' => $jenis
        ], 201);
    }

    public function show($id)
    {
        $jenis = JenisHadist::find($id);

        if (!$jenis) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis hadist tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail jenis hadist',
            'data' => $jenis
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $jenis = JenisHadist::find($id);

        if (!$jenis) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis hadist tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:191|unique:jenishadist,nama,' . $id,
        ]);

        $jenis->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Jenis hadist berhasil diperbarui',
            'data' => $jenis
        ], 200);
    }

    public function destroy($id)
    {
        $jenis = JenisHadist::find($id);

        if (!$jenis) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis hadist tidak ditemukan'
            ], 404);
        }

        $jenis->delete();

        return response()->json([
            'status' => true,
            'message' => 'Jenis hadist berhasil dihapus'
        ], 200);
    }
}
