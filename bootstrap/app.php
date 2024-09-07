<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/admin/api.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/auth/api.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/home/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'custom.throttle' => \App\Http\Middleware\CustomThrottleRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // dd($exceptions);
        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
            return response()->json([
            'type' => "https://datatracker.ietf.org/doc/html/rfc9110#section-15.5.5",
            'title' => 'اطلاعات شما معتبر نیست',
            'status' => 400,
        ], 400);
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
            'type' => "https://datatracker.ietf.org/doc/html/rfc9110#section-15.5.5",
            'title' => 'منبع درخواست یافت نشد',
            'status' => 404,
        ], 404);
        });
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            return response()->json([
            'type' => "https://datatracker.ietf.org/doc/html/rfc9110#section-15.5.5",
            'title' => 'در خواست بیش از حد مجاز',
            'status' => 429,
        ], 429);
        });
    })->create();
