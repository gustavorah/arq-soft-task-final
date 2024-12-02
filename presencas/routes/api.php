<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresencaController;

Route::get('/', [PresencaController::class, 'index']);
Route::get('/{id}', [PresencaController::class, 'show']);
Route::post('/', [PresencaController::class, 'store']);
Route::put('/{id}', [PresencaController::class, 'update']);
Route::delete('/{id}', [PresencaController::class, 'destroy']);
Route::post('/verificar-presenca', [PresencaController::class,'hasPresenca']);