<?php

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
    Route::get('/eventos', [EventosController::class,'index'])->name('eventos');
    Route::get('/dashboard', [InscricaoEventoController::class,'getAllInscricoes'])->name('dashboard');
    Route::post('/inscrever', [InscricaoEventoController::class,'store'])->name('inscrever');
    Route::get('/eventos/editar/{evento}', [EventosController::class, 'show'])->name('eventos.editar');
    Route::put('/eventos/atualizar/{evento}', [EventosController::class, 'atualizar'])->name('eventos.atualizar');
    Route::post('/presencas', [PresencaController::class,'store'])->name('presencas.marcar');
});

// Route::middleware(['auth', 'admin'])->group(function () {

// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
