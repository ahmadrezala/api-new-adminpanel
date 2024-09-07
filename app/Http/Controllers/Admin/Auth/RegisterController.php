<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Auth\RegisterApiRequest;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(RegisterApiRequest $request)
    {



        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        return ApiResponse::withMessage('User created successfully')
            ->withStatus(200)
            ->build()
            ->response();
    }


}
