<?php
namespace Api\ApiResponse;

use Api\ApiResponse\ApiResponseBuilder;
use Illuminate\Support\ServiceProvider;


class ApiResponseServiceProvider extends ServiceProvider
{


    public function register(): void
    {
        $this->app->bind('apiResponse',function(){
            return new ApiResponseBuilder;
        });
    }


    public function boot(): void
    {
        //
    }

}
