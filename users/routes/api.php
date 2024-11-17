<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);
Route::post('/', [UserController::class, 'store']);
Route::get('/{user}', [UserController::class, 'show']);
Route::put('/{user}', [UserController::class, 'update']);
Route::delete('/{user}', [UserController::class, 'destroy']);
