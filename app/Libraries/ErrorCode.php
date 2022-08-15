<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 16:08
 */

namespace App\Libraries;

class ErrorCode
{
    //系统
    const SYSTEM_ERROR                              = 100101;
    const SYSTEM_BUSY                               = 100102;

    //参数
    const PARAMETER_MISSING                         = 100201;
    const PARAMETER_INVALID                         = 100202;
    const PARAMETER_EXIST                           = 100203;
    const PARAMETER_VALIDATE_FAILED                 = 100204;
    const PARAMETER_SIGNATURE_INVALID               = 100205;

    //文件
    const FILE_EMPTY                                = 100301;
    const FILE_INVALID                              = 100302;
    const FILE_EXTENSION_INVALID                    = 100303;
    const FILE_NOT_EXIST                            = 100304;

    //网络
    const NETWORK_BUSY                              = 100401;
    const NETWORK_CONNECT_FAILED                    = 100402;
    const NETWORK_CONNECT_TIMEOUT                   = 100403;
    const NETWORK_CONNECT_CLOSED                    = 100404;

    //token
    const TOKEN_EXPIRED                             = 100501;
    const TOKEN_INVALID                             = 100502;

    //密码
    const PASSWORD_NOT_MATCH                        = 100601;

    //验证码
    const CAPTCHA_ERROR                             = 100701;

    //用户
    const USER_NOT_EXIST                            = 100901;
    const USER_MOBILE_EXIST                         = 100902;
    const USER_EMAIL_EXIST                          = 100903;

    //操作
    const OPERATION_FREQUENT                        = 101001;
    const OPERATION_INVALID                         = 101002;

    //保留错误码
    const UNAUTHENTICATED                           = 401;
    const NOT_FOUND                                 = 404;
    const PERMISSION_DENIED                         = 403;
    const METHOD_NOT_ALLOWED                        = 405;
}
