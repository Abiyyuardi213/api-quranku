<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AyatController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\DoaController;
use App\Http\Controllers\Api\HadistController;
use App\Http\Controllers\Api\JenisHadistController;
use App\Http\Controllers\Api\SurahController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/bookmark', [BookmarkController::class, 'index']);
    Route::post('/bookmark', [BookmarkController::class, 'store']);
    Route::get('/bookmark/{id}', [BookmarkController::class, 'show']);
    Route::put('/bookmark/{id}', [BookmarkController::class, 'update']);
    Route::delete('/bookmark/{id}', [BookmarkController::class, 'destroy']);
    Route::get('/bookmark/surah/{surahNumber}', [BookmarkController::class, 'bySurah']);
    Route::get('/bookmark-terbaru', [BookmarkController::class, 'latest']);
});

Route::get('/surah', [SurahController::class, 'index']);
Route::get('/surah/{id}', [SurahController::class, 'show']);
Route::post('/surah', [SurahController::class, 'store']);
Route::put('/surah/{id}', [SurahController::class, 'update']);
Route::delete('/surah/{id}', [SurahController::class, 'destroy']);
Route::get('/sync-surah', [SurahController::class, 'syncSurahFromApi']);

Route::get('/ayat', [AyatController::class, 'index']);
Route::get('/ayat/{id}', [AyatController::class, 'show']);
Route::get('/surah/{id}/ayat', [AyatController::class, 'bySurah']);

Route::post('/ayat', [AyatController::class, 'store']);
Route::put('/ayat/{id}', [AyatController::class, 'update']);
Route::delete('/ayat/{id}', [AyatController::class, 'destroy']);
Route::get('/sync-ayat-surah/{nomor}', [AyatController::class, 'syncAyatSurah']);
Route::get('/sync-ayat-semua', [AyatController::class, 'syncSemuaSurah']);

Route::get('/doa', [DoaController::class, 'index']);
Route::post('/doa', [DoaController::class, 'store']);
Route::get('/doa/acak', [DoaController::class, 'acak']);
Route::get('/doa/{id}', [DoaController::class, 'show']);
Route::put('/doa/{id}', [DoaController::class, 'update']);
Route::delete('/doa/{id}', [DoaController::class, 'destroy']);

Route::get('/jenis-hadist', [JenisHadistController::class, 'index']);
Route::post('/jenis-hadist', [JenisHadistController::class, 'store']);
Route::get('/jenis-hadist/{id}', [JenisHadistController::class, 'show']);
Route::put('/jenis-hadist/{id}', [JenisHadistController::class, 'update']);
Route::delete('/jenis-hadist/{id}', [JenisHadistController::class, 'destroy']);

Route::get('/hadist', [HadistController::class, 'index']);
Route::post('/hadist', [HadistController::class, 'store']);
Route::get('/hadist/acak', [HadistController::class, 'acak']);
Route::get('/hadist/{id}', [HadistController::class, 'show']);
Route::put('/hadist/{id}', [HadistController::class, 'update']);
Route::delete('/hadist/{id}', [HadistController::class, 'destroy']);
