<?php

use Illuminate\Support\Facades\Route;

Route::controller("CertificadoController")->group(function () {
    Route::get('/', 'index');
    Route::get('/{certificado}','show');
    Route::post('/', 'store');
    Route::put('/{certificado}', 'update');
    Route::delete('/{certificado}', 'destroy');
});
