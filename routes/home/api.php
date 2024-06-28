<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\PaymentController;
use App\Http\Controllers\Home\UserAddressController;

Route::prefix('home')->group(function(){

});



Route::post('/payment', [PaymentController::class, 'send'])->name('home.payment');
Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('home.payment_verify');
Route::get('/check-coupon', [PaymentController::class, 'checkCoupon']);
Route::apiResource('/addresses', UserAddressController::class)->except('show');
