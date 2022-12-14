<?php
/**
 * Created by PhpStorm.
 * Administrator: Administrator
 * Date: 2021/3/23
 * Time: 10:40
 */

if (! function_exists('setLanguage')) {
    /**
     * 设置语言
     */
    function setLanguage()
    {
        $locale = request()->header('x-api-locale', '');
        if (! $locale) {
            $locale = request()->input('locale', $locale);
        }
        if (! $locale) {
            $locale = config('app.locale');
        }
        app()->setLocale($locale);
    }
}

if (! function_exists('responseFormat')) {

    /**
     * @param array $response
     * @param string $requestId
     * @param int $status
     * @param string $format
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function responseFormat(array $response = [], string $requestId = '', int $status = 200, string $format = 'json')
    {
        $encrypt = config('app.encrypt');
        $format = $encrypt ? 'text/html; charset:utf-8' : 'application/' . $format . '; charset:utf-8';
        $headers = [
            'Content-Type' => $format,
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Expose-Headers' => 'Authorization,x-request-id',
            'Cache-Control' => 'no-cache',
        ];
        if ($encrypt) {
            $data = AESEncrypt(json_encode($response, JSON_UNESCAPED_UNICODE), $requestId);
            $response = response()->make($data, $status, $headers);
        } else {
            $response = response()->json($response, $status, $headers,JSON_UNESCAPED_UNICODE);
        }

        return $response;
    }
}

if (! function_exists('getRandomString')) {
    /**
     * 生成随机字符串
     * @param int $length
     * @return bool|string
     */
    function getRandomString(int $length = 42)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length * 2);
            if ($bytes !== false) {
                $str = substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length);
            }
        }
        $ret = isset($str) ? $str : substr(str_shuffle(str_repeat($pool, 5)), 0, $length);

        return $ret;
    }
}

if (! function_exists('getIp')) {
    /**
     * 获取Ip
     * @return array|false|string
     */
    function getIp() {
        if (getenv('HTTP_X_REAL_IP') && strcasecmp(getenv('HTTP_X_REAL_IP'), 'unknown')) {
            $ip = getenv('HTTP_X_REAL_IP');
        } elseif (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = 'unknown';
        }

        return $ip;
    }
}

if (! function_exists('AESEncrypt')) {
    /**
     * AES数据加密
     * @param $data
     * @param string $key
     * @return string
     */
    function AESEncrypt($data, string $key = '')
    {
        $key = empty($key) ? bin2hex(base64_decode(substr(config('app.key'), 7))) : $key;
        if (strlen($key) >16) {
            $key = substr($key, 0, 16);
        }

        return  base64_encode(openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA , $key));
    }
}

if (! function_exists('AESDecrypt')) {
    /**
     * AES数据解密
     * @param $data
     * @param string $key
     * @return string
     */
    function AESDecrypt($data, string $key = '')
    {
        $ret = $data;
        $key = empty($key) ? bin2hex(base64_decode(substr(config('app.key'), 7))) : $key;
        if (strlen($key) >16) {
            $key = substr($key, 0, 16);
        }
        if ($data && $data == base64_encode(base64_decode($data))) {
            $ret = openssl_decrypt(base64_decode($data), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $key);
        }

        return $ret;
    }
}
