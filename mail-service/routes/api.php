<?php

use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

Route::get("/", [EmailController::class,"sendEmail"]);