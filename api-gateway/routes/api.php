<?php

use App\Http\Controllers\ApiGatewayController;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::any('{service}/{path?}', [ApiGatewayController::class, 'forwardRequest'])
    ->where('path', '.*');
