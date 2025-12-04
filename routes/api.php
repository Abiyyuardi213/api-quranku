<?php

use App\Http\Controllers\Api\AyatController;
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
