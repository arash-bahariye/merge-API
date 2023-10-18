<?php

use App\Http\Controllers\ExternalApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/index', [ExternalApiController::class, 'index']);
Route::get('/{country_code}', [ExternalApiController::class, 'show']);
