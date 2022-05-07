<?php

namespace App\Exceptions;

use App\Exceptions\NoPermissionException;
use App\Facades\Support\Jsend;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            // report($e);
        });

        // Global handle NoPermisisonException
        $this->renderable(function (NoPermissionException $e, $request) {
            // if ($request->expectsJson()) {
            //     return Jsend::sendError($e->getMessage(), [], 403);
            // }

            // return abort(403, $e->getMessage());
            throw new AccessDeniedHttpException($e->getMessage(), $e);
        });

        $this->renderable(function (FailException $e, $request) {
            if ($request->ajax()) {
                throw new HttpException(400, $e->getMessage());
            }

            return abort(400, $e->getMessage());
        });
    }

    /**
     * custom exception
     * @param  \Throwable $e [description]
     * @return [type]        [description]
     */
    protected function prepareException(\Throwable $e)
    {
        // Global handle for miss match csrf token
        if ($e instanceof TokenMismatchException) {
            $e = new HttpException(
                419,
                __('Sorry, your session has expired.'),
                $e
            );
        }

        // Global handle ModelNotFoundException
        if ($e instanceof ModelNotFoundException) {
            $model = explode('\\', $e->getModel());
            $modelName = __(end($model));
            $message = __(':Name Not Found', ['Name' => $modelName]);

            $e = new NotFoundHttpException($message, $e);
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
            return Jsend::sendError(
                __('You are not logged in'),
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
