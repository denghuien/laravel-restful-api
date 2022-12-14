<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 15:55
 */

namespace App\Http\Middleware;

use Closure;
use App\Services\AccessServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiResponse
{
    /**
     * @param $request
     * @param Closure $next
     * @return JsonResponse|\Illuminate\Http\Response|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $code = "0";
        $message = 'success';
        if ($response->getContent() === false || $response->isRedirection()) {
            return $response;
        }
        $data = $response->getOriginalContent();
        if ($response->exception) {
            $code = $response->exception->getCode();
            $message = $response->exception->getMessage();
            $data = null;
        }
        $status = $response->status();
        $time = microtime(true);
        $header = $request->header();
        $requestId = $header['request-id'][0] ?? '';
        $ip = getIp();
        $method = $request->method();
        $action = $request->route() ? $request->route()->getAction() : [];
        $path = $request->path();
        $parameter = $request->input();
        $tokenId = Auth::user() ? md5(auth('api')->getToken()) : "0";
        $response = ['timestamp' => $time, 'code' => (string) $code, 'message' => $message, 'data' => $data];
        $serviceDiary = new AccessServices($method, $path, $time, $ip, $response, $action, $parameter, $header, $tokenId, $status);
        $serviceDiary->create();

        return responseFormat($response, $requestId, $status, 'json');
    }
}
