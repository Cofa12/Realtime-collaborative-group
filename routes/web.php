<?php

use App\Http\Controllers\PusherAuthController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::post('/pusher/auth', [PusherAuthController::class, 'authenticate']);
