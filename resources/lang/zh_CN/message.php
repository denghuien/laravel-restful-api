<?php

use App\Libraries\ErrorCode;

return [
    //system
    ErrorCode::SYSTEM_ERROR                             => '系统错误',
    ErrorCode::SYSTEM_BUSY                              => '系统繁忙',

    //parameter
    ErrorCode::PARAMETER_MISSING                        => '参数缺省',
    ErrorCode::PARAMETER_INVALID                        => '参数无效',
    ErrorCode::PARAMETER_EXIST                          => '参数已存在',
    ErrorCode::PARAMETER_VALIDATE_FAILED                => '参数校验失败',
    ErrorCode::PARAMETER_SIGNATURE_INVALID              => '参数签名无效',

    //file
    ErrorCode::FILE_EMPTY                               => '文件为空',
    ErrorCode::FILE_INVALID                             => '文件无效',
    ErrorCode::FILE_EXTENSION_INVALID                   => '文件后缀无效',
    ErrorCode::FILE_NOT_EXIST                           => '文件不存在',

    //network
    ErrorCode::NETWORK_BUSY                             => '网络繁忙',
    ErrorCode::NETWORK_CONNECT_FAILED                   => '网络连接失败',
    ErrorCode::NETWORK_CONNECT_TIMEOUT                  => '网络连接超时',
    ErrorCode::NETWORK_CONNECT_CLOSED                   => '网络连接关闭',

    //token
    ErrorCode::TOKEN_EXPIRED                            => '令牌过期',
    ErrorCode::TOKEN_INVALID                            => '令牌无效',

    //password
    ErrorCode::PASSWORD_NOT_MATCH                       => '密码不匹配',

    //captcha
    ErrorCode::CAPTCHA_ERROR                            => '验证码错误',

    //user
    ErrorCode::USER_NOT_EXIST                           => '用户不存在',
    ErrorCode::USER_MOBILE_EXIST                        => '用户手机存在',
    ErrorCode::USER_EMAIL_EXIST                         => '用户邮箱存在',

    //operation
    ErrorCode::OPERATION_FREQUENT                       => '操作频繁',
    ErrorCode::OPERATION_INVALID                        => '操作无效',

    //retain
    ErrorCode::UNAUTHENTICATED                          => '未认证',
    ErrorCode::NOT_FOUND                                => '页面未找不到',
    ErrorCode::PERMISSION_DENIED                        => '权限禁止',
    ErrorCode::METHOD_NOT_ALLOWED                       => '请求方式不允许',

];
