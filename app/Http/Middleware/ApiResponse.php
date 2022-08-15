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
use Illuminate\Support\Facades\Auth;

class ApiResponse
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     * @throws \ReflectionException
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $code = 0;
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

        return responseFormat($data, $message, $code, $status, $time);
    }
}
