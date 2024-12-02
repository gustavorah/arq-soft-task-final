<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SincronizacaoController;

Route::get('/sincronizar-dados', [SincronizacaoController::class, 'sincronizar']);
Route::post('/set-offline-mode', [SincronizacaoController::class, 'setOfflineMode']);
