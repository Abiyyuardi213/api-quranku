<?php

use App\Http\Controllers\Api\AyatController;
use App\Http\Controllers\Api\DoaController;
use App\Http\Controllers\Api\JenisHadistController;
use App\Http\Controllers\Api\SurahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
Route::put('/ayat/{id}', [AyatController::class,     'update']);
Route::delete('/ayat/{id}', [AyatController::class, 'destroy']);
Route::get('sync-ayat-surah/{nomor}', [AyatController::class, 'syncAyatSurah']);
Route::get('/sync-ayat-semua', [AyatController::class, 'syncSemuaSurah']);

Route::get('/doa', [DoaController::class, 'index']);
Route::post('/doa', [DoaController::class, 'store']);
Route::get('/doa/{id}', [DoaController::class, 'show']);
Route::put('/doa/{id}', [DoaController::class, 'update']);
Route::delete('/doa/{id}', [DoaController::class, 'destroy']);

Route::get('/jenis-hadist', [JenisHadistController::class, 'index']);
Route::post('/jenis-hadist', [JenisHadistController::class, 'store']);
Route::get('/jenis-hadist/{id}', [JenisHadistController::class, 'show']);
Route::put('/jenis-hadist/{id}', [JenisHadistController::class, 'update']);
Route::delete('/jenis-hadist/{id}', [JenisHadistController::class, 'destroy']);
