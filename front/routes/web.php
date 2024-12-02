<?php

use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\InscricaoEventoController;
use App\Http\Controllers\PresencaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth','verified'])->group(function () {
    Route::controller(EventosController::class)->group(function() {
        Route::get('/eventos', 'index')->name('eventos');
        Route::get('/eventos/editar/{evento}',  'show')->name('eventos.editar');
        Route::put('/eventos/atualizar/{evento}',  'atualizar')->name('eventos.atualizar');
        Route::get('/eventos/inscrever-pessoa/{evento}', 'inscreverPessoa')->name('eventos.inscrever');
    });

    Route::controller(InscricaoEventoController::class)->group(function() {
        Route::get('/dashboard', 'getAllInscricoes')->name('dashboard');
        Route::post('/inscrever', 'store')->name('inscrever');
        Route::post('/inscrever-rapido', 'storeRapido')->name('inscrever-rapido');
        Route::delete('/inscricao-evento/{id}', 'cancelar')->name('inscricao.cancelar');
    });

    // Route::controller(Certificado)
    Route::post('/presencas', [PresencaController::class,'store'])->name('presencas.marcar');

    Route::controller(CertificadoController::class)->group(function() {
        Route::post('/gerar-certificado/{ref_inscricao}', 'gerar')->name('certificado.gerar');
    });
});

// Route::middleware(['auth', 'admin'])->group(function () {

// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
