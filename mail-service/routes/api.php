<?php

use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;
Route::controller(EmailController::class)->group(function () {
    Route::post("/inscricao", "sendEmailInscricao");
    Route::post("/presenca", "sendEmailPresenca");
    Route::post("/cancelamento", "sendEmailCancelamento");
});