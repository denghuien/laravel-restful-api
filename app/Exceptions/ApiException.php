<?php

namespace App\Exceptions;

use App\Libraries\ErrorCode;

class ApiException  extends \Exception
{
    /**
     * @param int $code
     * @param string $message
     */
    function __construct($code = 0, $message = '')
    {
        setLanguage();
        $code = is_int($code) ? $code : ErrorCode::SYSTEM_ERROR;
        $message = ! $message ? self::getMessageByCode($code) : $message;

        parent::__construct($message, $code);
    }

    /**
     * @param int $code
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|int|string|null
     */
    public static function getMessageByCode(int $code = 0)
    {
        $message = '';
        $class = new \ReflectionClass(ErrorCode::class);
        $constants = array_flip($class->getConstants());
        if (isset($constants[$code])) {
            if (array_key_exists($code, trans('message'))) {
                $message = trans('message.' . $code);
            } else {
                $message = $constants[$code];
            }
        }

        return $message;
    }
}
