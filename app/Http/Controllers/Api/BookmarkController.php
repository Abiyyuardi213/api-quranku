<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        $data = Bookmark::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => 'List semua bookmark',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'surahNumber' => 'required|integer',
            'surahName'   => 'required|string|max:255',
            'ayahNumber'  => 'required|integer',
            'arabicText'  => 'required|string',
            'translation' => 'required|string',
            'timestamp'   => 'nullable|integer'
        ]);

        $bookmark = Bookmark::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Bookmark berhasil ditambahkan',
            'data' => $bookmark
        ], 201);
    }

    public function show($id)
    {
        $bookmark = Bookmark::find($id);

        if (!$bookmark) {
            return response()->json([
                'status' => false,
                'message' => 'Bookmark tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail bookmark',
            'data' => $bookmark
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $bookmark = Bookmark::find($id);

        if (!$bookmark) {
            return response()->json([
                'status' => false,
                'message' => 'Bookmark tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'surahNumber' => 'sometimes|required|integer',
            'surahName'   => 'sometimes|required|string|max:255',
            'ayahNumber'  => 'sometimes|required|integer',
            'arabicText'  => 'sometimes|required|string',
            'translation' => 'sometimes|required|string',
            'timestamp'   => 'nullable|integer'
        ]);

        $bookmark->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Bookmark berhasil diperbarui',
            'data' => $bookmark
        ], 200);
    }

    public function destroy($id)
    {
        $bookmark = Bookmark::find($id);

        if (!$bookmark) {
            return response()->json([
                'status' => false,
                'message' => 'Bookmark tidak ditemukan'
            ], 404);
        }

        $bookmark->delete();

        return response()->json([
            'status' => true,
            'message' => 'Bookmark berhasil dihapus'
        ], 200);
    }

    public function bySurah($surahNumber)
    {
        $data = Bookmark::where('surahNumber', $surahNumber)
            ->orderBy('ayahNumber', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => "Bookmark untuk surah ke-$surahNumber",
            'data' => $data
        ], 200);
    }

    public function latest()
    {
        $data = Bookmark::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => true,
            'message' => "5 bookmark terbaru",
            'data' => $data
        ], 200);
    }
}
