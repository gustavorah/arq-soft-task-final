<?php

use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;

Route::controller(CertificadoController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{certificado}','show');
    Route::post('/', 'store');
    Route::put('/{certificado}', 'update');
    Route::delete('/{certificado}', 'destroy');
});
