<?php

use App\Http\Controllers\EventosController;
use Illuminate\Support\Facades\Route;

Route::controller(EventosController::class)->group(function () {
    Route::post('/', 'store');
    Route::get('/', 'index');
    Route::get('/{evento}', 'show');
    Route::put('/{evento}', 'update');
    Route::delete('/{evento}', 'destroy');
});
