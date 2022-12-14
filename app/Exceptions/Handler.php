<?php

namespace App\Exceptions;

use Throwable;
use App\Services\AccessServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
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
     * @param Throwable $exception
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function render($request, Throwable $exception)
    {
        setLanguage();
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
        $header = $request->header();
        $requestId = $header['request-id'][0] ?? '';
        $ip = getIp();
        $method = $request->method();
        $action = $request->route() ? $request->route()->getAction() : [];
        $path = $request->path();
        $parameter = $request->input();
        $tokenId = Auth::user() ? md5(auth('api')->getToken()) : "0";
        $response = ['timestamp' => $time, 'code' => (string) $code, 'message' => $message, 'data' => null];
        $serviceDiary = new AccessServices($method, $path, $time, $ip, $response, $action, $parameter, $header, $tokenId, $status);
        $serviceDiary->create();

        return responseFormat($response, $requestId, $status, 'json');
    }
}
