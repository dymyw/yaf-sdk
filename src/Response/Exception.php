<?php

namespace Dymyw\Yaf\Response;

use Throwable;

class Exception extends \Exception
{
    const ERR_UNKNOWN   = 999999;
    const ERR_CODE_MAP  = [
        999999 => '未知错误',
        999998 => '系统错误',
        999997 => '无此权限',
        999996 => '参数错误',
    ];

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        if (!$code) {
            $code = self::ERR_UNKNOWN;
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * 抛出错误异常
     *
     * @param $code
     * @param string $exMessage
     * @return Exception
     */
    public static function error($code, $exMessage = '')
    {
        $message = self::getErrMsg($code);

        $exception = new self($message . " " . $exMessage, $code);

        return $exception;
    }

    /**
     * 获取错误 map
     *
     * @return array
     */
    protected static function getCodeMap()
    {
        return self::ERR_CODE_MAP;
    }

    /**
     * 根据 code 获取错误 message
     *
     * @param $code
     * @return mixed|string
     */
    private static function getErrMsg($code)
    {
        $codeMap = static::getCodeMap();

        if (!isset($codeMap[$code])) {
            return "未定义的错误码[{$code}]";
        }

        return $codeMap[$code];
    }
}
