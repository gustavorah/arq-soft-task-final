<?php

use App\Http\Controllers\InscricaoEventoController;
use Illuminate\Support\Facades\Route;

Route::controller(InscricaoEventoController::class)->group(function() {
    Route::get("/", 'index');
    Route::get('/{inscricao_evento}','show');
    Route::post("/", 'store');
    Route::put("/{inscricao_evento}", 'update');
    Route::delete('/{inscricao_evento}','destroy');
});