<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController; // Pastikan ini ada
use App\Http\Controllers\Api\JournalSourceController; // Pastikan ini ada
use App\Http\Controllers\Api\StatsController;

// Rute Publik
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/articles/search', [ArticleController::class, 'search']); // Ini rute pencarian kita
Route::get('/stats', [StatsController::class, 'index']);

// Rute yang Dilindungi
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('journal-sources', JournalSourceController::class);
});