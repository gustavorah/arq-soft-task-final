<?php

use App\Http\Controllers\ApiGatewayController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatabaseController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\LogRoute;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::post('jwt-token', [AuthController::class,'login']);

Route::any('certificado/autenticar', [ApiGatewayController::class, 'forwardRequest'])->withoutMiddleware([Authenticate::class]);

// Route::any('certificado', [ApiGatewayController::class, 'forwardRequest'])->withoutMiddleware([Authenticate::class]);

Route::post('logout', [AuthController::class,'logout'])->middleware(Authenticate::class);

Route::any('users/auth', [ApiGatewayController::class, 'forwardRequest']);

Route::any('{service}/{path?}', [ApiGatewayController::class, 'forwardRequest'])
    ->where('path', '.*')->middleware([LogRoute::class])->middleware([Authenticate::class]);

