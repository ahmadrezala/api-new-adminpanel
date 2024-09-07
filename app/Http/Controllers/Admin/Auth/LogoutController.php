<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Api\ApiResponse\Facades\ApiResponse;

class LogoutController extends Controller
{
    public function __invoke()
    {

        Auth::user()->tokens()->delete();

        return ApiResponse::withMessage('User logged out successfully')
            ->withStatus(200)
            ->build()
            ->response();
    }
}
