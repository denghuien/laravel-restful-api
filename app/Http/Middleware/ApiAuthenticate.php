<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16
 * Time: 12:23
 */

namespace App\Http\Middleware;

use Closure;
use App\Services\TokenServices;
use App\Services\DeviceServices;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class ApiAuthenticate extends BaseMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $time = time();
        $tokenExpire = JWTAuth::factory()->getTTL()* 60;
        $blackListExpire = config('jwt.blacklist_grace_period');
        try {
            $user = $this->auth->parseToken()->authenticate();
            if (! $user) {

                throw new AuthenticationException();
            }
        } catch (JWTException $exception) {
            if ($exception instanceof TokenExpiredException) {
                try {
                    $claim = $this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray();
                    $expire = $claim['exp'];
                    $userId = $claim['sub'];
                    $tokenOld = $this->auth->getToken();
                    $parentId = md5($tokenOld);
                    if (($time - $expire) <= $tokenExpire * 2 + $blackListExpire) {
                        $token = $this->auth->refresh();
                        JWTAuth::setToken($token)->toUser();
                        $serviceDevice = new DeviceServices($userId);
                        $deviceId = $serviceDevice->register();
                        $serviceToken = new TokenServices($userId, $deviceId, $token, $expire, $parentId);
                        $serviceToken->register();
                        $request->headers->set('Authorization','Bearer '.$token);

                        return $this->setAuthenticationHeader($next($request), $token);
                    }
                    throw new AuthenticationException();
                } catch (\Exception $exception) {
                    throw new AuthenticationException($exception->getMessage());
                }
            } else {
                throw new AuthenticationException($exception->getMessage());
            }
        }

        return $next($request);
    }
}
