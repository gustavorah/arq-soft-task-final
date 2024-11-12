<?php

use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::get('/', function () {
        return 's';
    });
});
