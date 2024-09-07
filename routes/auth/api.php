<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\LogoutController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {

    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class)->middleware('custom.throttle:3,1');
    Route::post('logout', LogoutController::class);
});

