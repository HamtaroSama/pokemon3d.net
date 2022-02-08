<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    protected $dontFlash = ["password", "password_confirmation"];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Sentry Error Reporting
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e) && app()->bound("sentry")) {
                app("sentry")->captureException($e);
            }
        });

        // Custom Error Handling for 404 in API
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is("api/*")) {
                return response()->json(
                    [
                        "message" => "No records found.",
                    ],
                    404
                );
            }
        });
    }
}
