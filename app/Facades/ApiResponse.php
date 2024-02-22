<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use phpDocumentor\Reflection\Types\Mixed_;

/**
 * We will use this ApiResponse as a standard api response for our application.
 * It will make it easy to handle and exception in frontend application
 * without worrying about what type of response will be received.
 *
 * return (new ApiResponse())->success('Success Message.', $data);
 *
 * @method static \App\Helpers\ApiResponse success(string $message, array $payload = [], integer $status_code = 200)
 * @method static \App\Helpers\ApiResponse error(string $message, array $payload = [], integer $status_code = 422, $exception = null)
 *
 * @see \Illuminate\View\Factory
 */
class ApiResponse extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'api-response';
    }
}
