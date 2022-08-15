<?php

namespace App\Exceptions;

use App\Libraries\ErrorCode;
use App\Services\AccessServices;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            $code = ErrorCode::UNAUTHENTICATED;
            $message = ApiException::getMessageByCode($code);
            $status = 401;
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $code = ErrorCode::METHOD_NOT_ALLOWED;
            $message = ApiException::getMessageByCode($code);
            $status = 405;
        } elseif ($exception instanceof NotFoundHttpException) {
            $code = ErrorCode::NOT_FOUND;
            $message = ApiException::getMessageByCode($code);
            $status = 404;
        } elseif ($exception instanceof HttpException) {
            $code = ErrorCode::PERMISSION_DENIED;
            $message = ApiException::getMessageByCode($code);
            $status = 403;
        } elseif ($exception instanceof QueryException) {
            $code = ErrorCode::SYSTEM_ERROR;
            $message = $exception->getMessage();
            $status = 400;
        } elseif ($exception instanceof ApiException) {
            $code = $exception->getCode();
            $message = $exception->getMessage();
            $status = 400;
        } else {
            $code = ErrorCode::NETWORK_BUSY;
            $message = $exception->getMessage() ? $exception->getMessage() : ApiException::getMessageByCode($code);
            $status = 400;
        }
        $time = microtime(true);
        if ($request->route()) {
            $auth = Auth::user();
            $tokenId = 0;
            $ip = getIp();
            $method = $request->method();
            $action = $request->route()->getAction();
            $path = $request->path();
            $parameter = $request->input();
            $header = $request->header();
            if ($auth) {
                $token = auth('api')->getToken();
                $tokenId = md5($token);
            }
            $serviceDiary = new AccessServices($method, $time, $ip, $action, $path, $parameter, $header, $tokenId, $status);
            $serviceDiary->create();
        }

        return responseFormat(null, $message, $code, $status, $time);
    }
}
