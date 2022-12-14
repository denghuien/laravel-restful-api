<?php

namespace App\Exceptions;

class ApiException  extends \Exception
{
    /**
     * @param string $code
     * @param string $message
     */
    function __construct($code = "0", $message = '')
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
            $error = is_array(trans('message')) ? trans('message') : [];
            if (array_key_exists($code, $error)) {
                $message = trans('message.' . $code);
            } else {
                $message = $constants[$code];
            }
        }

        return $message;
    }
}
