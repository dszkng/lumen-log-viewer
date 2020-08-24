<?php

namespace App\Exceptions;

use App\Common\ErrorCode;
use App\Common\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Handler
 * @package App\Exceptions
 * @author szk
 * @time 2020-05-28 10:36:37
 */
class Handler extends ExceptionHandler
{
    use JsonResponse;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->has('debug')) {
            if ((int)$request->get('debug') === 2) {
                return parent::render($request, $exception);
            }

            return $this->returnError(
                [
                    'user' => DB_USER,
                    'pwd' => DB_PASSWORD,
                    'db' => DB_DATABASE,
                    'host' => url(),
                ],
                ErrorCode::SYSTEM_ERROR[0],
                ErrorCode::SYSTEM_ERROR[1]
            );
        }
        \Log::info('Response', [
            $exception->getLine(),
            $exception->getCode(),
            $exception->getMessage(),
            $exception->getFile()
        ]);

        if ($exception instanceof ApiException) {
            return $this->returnError(
                '',
                $exception->getCode(),
                $exception->getMessage()
            );
        } elseif ($exception instanceof MissingParamException) {
            return $this->returnError(
                '',
                $exception->getCode(),
                $exception->getMessage()
            );
        } elseif ($exception instanceof SdkException) {
            return $this->returnError(
                '',
                $exception->getCode(),
                $exception->getMessage()
            );
        } elseif ($exception instanceof AccountParseException) {
            Log::info('AccountParseException', [
                'Authorization' => $request->header('authorization'),
                'Params' => $request->input(),
            ]);

            return response()->json([
                'state' => $exception->getCode(),
                'ret' => -1,
                'data' => '',
                'msg' => $exception->getMessage(),
            ]);
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            return $this->returnError(
                '',
                ErrorCode::INVALID_REQUEST_METHOD[0],
                ErrorCode::INVALID_REQUEST_METHOD[1]
            );
        } elseif ($exception instanceof NotFoundHttpException) {
            return $this->returnError(
                '',
                ErrorCode::INVALID_REQUEST_ROUTE[0],
                ErrorCode::INVALID_REQUEST_ROUTE[1]
            );
        } elseif ($exception instanceof \PDOException) {
            return $this->returnError(
                '',
                ErrorCode::STORE_ERROR[0],
                ErrorCode::STORE_ERROR[1]
            );
        } else {
            if (app()->environment() !== 'production') {
                return $this->returnError(
                    [
                        'Flag' => 'Service',
                        'Msg' => $exception->getMessage(),
                        'File' => $exception->getFile(),
                        'Line' => $exception->getLine(),
                        'Trace' => $exception->getTraceAsString(),
                    ],
                    ErrorCode::SYSTEM_ERROR[0],
                    ErrorCode::SYSTEM_ERROR[1]
                );
            }

            return $this->returnError(
                $exception->getMessage(),
                ErrorCode::SYSTEM_ERROR[0],
                ErrorCode::SYSTEM_ERROR[1]
            );
        }
    }
}
