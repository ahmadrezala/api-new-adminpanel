<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


// var_dump(app('apiResponse'));
Route::get('/', function () {

    return view('welcome');
});


Route::get('/payment/verify', function (Request $request) {

    // dd($request->all());
    $response = Http::post('http://localhost/trydargah/api/public/api/payment/verify', [
        'merchant' => env('ZIBAL_IR_API_KEY'),
        'trackId' => $request->trackId
    ]);

    dd($response->json());
});
