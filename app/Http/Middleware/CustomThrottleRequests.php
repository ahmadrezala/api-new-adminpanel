<?php

namespace App\Http\Middleware;


use Illuminate\Routing\Middleware\ThrottleRequests;

class CustomThrottleRequests extends ThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    protected function resolveRequestSignature($request)
    {


        if ($ip = $request->ip()) {

            return sha1($ip);
        }


        return parent::resolveRequestSignature($request);
    }
}
