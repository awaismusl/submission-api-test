<?php

namespace App\Exceptions;

use App\Facades\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if (($request->is('api/*') || $request->wantsJson()) && ! $e instanceof ValidationException) {
                $data = [];

                $status_code = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;

                if ($e instanceof AuthenticationException) {
                    $status_code = 401;
                } elseif ($e instanceof AuthorizationException) {
                    $status_code = 403;
                } elseif ($e instanceof ModelNotFoundException) {
                    $status_code = 404;
                } elseif ($e instanceof NotFoundHttpException) {
                    $status_code = 404;
                } elseif ($e instanceof MethodNotAllowedHttpException) {
                    $status_code = 405;
                } elseif ($e instanceof ConnectionException || $e instanceof RequestException) {
                    $status_code = 422;
                } elseif ($e instanceof HttpException) {
                    $status_code = $e->getStatusCode();
                }

                $message = $e->getMessage();

                // hide specific error to public. e.g. SQL error
                if ($status_code === 500) {
                    $message = 'Internal Server Error.';
                }

                return ApiResponse::error($message, $data, $status_code, $e);
            }
        });
    }
}
