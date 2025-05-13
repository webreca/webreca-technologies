<?php

use App\Http\Controllers\TwilioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/webhook/whatsapp', [TwilioController::class, 'handleIncomingMessage']);
