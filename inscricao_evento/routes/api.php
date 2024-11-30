<?php

use App\Http\Controllers\InscricaoEventoController;
use Illuminate\Support\Facades\Route;

Route::controller(InscricaoEventoController::class)->group(function() {
    Route::post("/inscricoes-user", 'getInscricoesByUser');
    Route::post('/inscricoes','getAllInscricoesByRefEvento');
    Route::get('/{inscricao_evento}','show');
    Route::post("/", 'store');
    Route::put("/{inscricao_evento}", 'update');
    Route::delete('/{inscricao_evento}','destroy');
    Route::post("/verificar-inscricao", 'hasInscricaoByUserAndEvento');
});