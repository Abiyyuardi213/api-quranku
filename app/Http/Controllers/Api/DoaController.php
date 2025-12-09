<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doa;
use Illuminate\Http\Request;

class DoaController extends Controller
{
    public function index()
    {
        $data = Doa::orderBy('createdAt', 'desc')->get();

        return response()->json([
            'status'  => true,
            'message' => 'List semua doa',
            'data'    => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sumber' => 'required|string|max:50',
            'judul'  => 'required|string|max:255',
            'arab'   => 'nullable|string',
            'indo'   => 'nullable|string',
        ]);

        $doa = Doa::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Doa berhasil ditambahkan',
            'data'    => $doa
        ], 201);
    }

    public function show($id)
    {
        $doa = Doa::find($id);

        if (!$doa) {
            return response()->json([
                'status'  => false,
                'message' => 'Doa tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail doa',
            'data'    => $doa
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $doa = Doa::find($id);

        if (!$doa) {
            return response()->json([
                'status'  => false,
                'message' => 'Doa tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'sumber' => 'sometimes|required|string|max:50',
            'judul'  => 'sometimes|required|string|max:255',
            'arab'   => 'nullable|string',
            'indo'   => 'nullable|string',
        ]);

        $doa->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Doa berhasil diperbarui',
            'data'    => $doa
        ], 200);
    }

    public function destroy($id)
    {
        $doa = Doa::find($id);

        if (!$doa) {
            return response()->json([
                'status'  => false,
                'message' => 'Doa tidak ditemukan'
            ], 404);
        }

        $doa->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Doa berhasil dihapus'
        ], 200);
    }
}
