<?php

namespace App\Http\Controllers\Admin\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Auth\LoginApiRequest;

class LoginController extends Controller
{
    public function __invoke(LoginApiRequest $request)
    {


        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('login_user')->accessToken;
            return ApiResponse::withData([
                'token' => $token,
                'user' => [
                    'email' => $user->email,
                    'name' => $user->name,
                ]

            ])
                ->withStatus(200)
                ->build()
                ->response();
        } else {
            return ApiResponse::withAppends([
                'type' => "https://datatracker.ietf.org/doc/html/rfc9110#section-15.5.5",
                'title' => 'اطلاعات شما معتبر نیست',
                'status' => 400,
            ])
                ->withStatus(400)
                ->build()
                ->response();

        }



    }
}
