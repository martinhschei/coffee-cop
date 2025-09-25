<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/observation', [App\Http\Controllers\Api\ObservationController::class, 'store']);
