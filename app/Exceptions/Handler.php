<?php

namespace App\Exceptions;

use App\Exceptions\NoPermissionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NoPermissionException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 403);
            }
            
            return abort(403, $e->getMessage());
        });
    }

    /**
     * custom exception
     * @param  \Throwable $e [description]
     * @return [type]        [description]
     */
    protected function prepareException(\Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            $e = new HttpException(419, __('unauthenticated'), $e);
        }

        return parent::prepareException($e);
    }

    /**
     * handling when the user is not authenticated
     * @param  [Request]                  $request   [description]
     * @param  AuthenticationException $exception [description]
     * @return [type]                             [description]
     */
    protected function unauthenticated(
        $request,
        AuthenticationException $exception
    ) {
        if ($request->expectsJson()) {
            return \Jsend::sendError(
                __('unauthenticated'),
                [
                    'links' => [
                        'redirectTo' => route('login.form')
                    ]
                ],
                401
            );
        }

        return redirect()->guest(route('login.form'));
    }
}
