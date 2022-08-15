<?php

use App\Libraries\ErrorCode;

return [
    //system
    ErrorCode::SYSTEM_ERROR                             => 'System error.',
    ErrorCode::SYSTEM_BUSY                              => 'System busy.',

    //parameter
    ErrorCode::PARAMETER_MISSING                        => 'Parameter missing.',
    ErrorCode::PARAMETER_INVALID                        => 'Parameter invalid.',
    ErrorCode::PARAMETER_EXIST                          => 'Parameter exist.',
    ErrorCode::PARAMETER_VALIDATE_FAILED                => 'Parameter validate failed.',
    ErrorCode::PARAMETER_SIGNATURE_INVALID              => 'Parameter signature invalid.',

    //file
    ErrorCode::FILE_EMPTY                               => 'File empty.',
    ErrorCode::FILE_INVALID                             => 'File invalid.',
    ErrorCode::FILE_EXTENSION_INVALID                   => 'File extension invalid.',
    ErrorCode::FILE_NOT_EXIST                           => 'File not exist.',

    //network
    ErrorCode::NETWORK_BUSY                             => 'Network busy.',
    ErrorCode::NETWORK_CONNECT_FAILED                   => 'Network connect failed.',
    ErrorCode::NETWORK_CONNECT_TIMEOUT                  => 'Network connect timeout.',
    ErrorCode::NETWORK_CONNECT_CLOSED                   => 'Network connect closed.',

    //token
    ErrorCode::TOKEN_EXPIRED                            => 'Token expired.',
    ErrorCode::TOKEN_INVALID                            => 'Token invalid.',

    //password
    ErrorCode::PASSWORD_NOT_MATCH                       => 'Password not match.',

    //captcha
    ErrorCode::CAPTCHA_ERROR                            => 'Captcha error.',

    //user
    ErrorCode::USER_NOT_EXIST                           => 'User not exist.',
    ErrorCode::USER_MOBILE_EXIST                        => 'User mobile exist.',
    ErrorCode::USER_EMAIL_EXIST                         => 'User email exist.',

    //operation
    ErrorCode::OPERATION_FREQUENT                       => 'Operation frequent.',
    ErrorCode::OPERATION_INVALID                        => 'Operation invalid.',

    //retain
    ErrorCode::UNAUTHENTICATED                          => 'Unauthenticated.',
    ErrorCode::NOT_FOUND                                => 'Not found.',
    ErrorCode::PERMISSION_DENIED                        => 'Permission denied.',
    ErrorCode::METHOD_NOT_ALLOWED                       => 'Method not allowed.',

];
