<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($message = null, $data = [], $status = null) {
            return \ApiResponse::success($message, $data, $status);
        });

        Response::macro('error', function ($message = null, $data = [], $status = null) {
            return \ApiResponse::error($message, $data, $status);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('api-response', \App\Helpers\ApiResponse::class);
    }
}
