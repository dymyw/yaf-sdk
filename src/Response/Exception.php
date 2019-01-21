<?php

namespace Dymyw\Yaf\Response;

use Throwable;

/**
 * Class Exception
 * @package Dymyw\Yaf\Response
 */
class Exception extends \Exception
{
    const ERR_UNKNOWN               = 999999;
    const ERR_SYSTEM                = 999998;
    const ERR_NOT_AUTH              = 999997;
    const ERR_PARAMS                = 999996;
    const ERR_REQUEST_METHOD        = 999995;

    const ERR_CODE_MAP  = [
        self::ERR_UNKNOWN           => '未知错误',
        self::ERR_SYSTEM            => '系统错误',
        self::ERR_NOT_AUTH          => '无此权限',
        self::ERR_PARAMS            => '参数错误',
        self::ERR_REQUEST_METHOD    => '请求方式错误',
    ];

    /**
     * Exception constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
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
    public static function error($code, string $exMessage = '')
    {
        $message = self::getErrMsg($code);
        if ($exMessage) {
            $message .= ' ' . $exMessage;
        }

        $exception = new self($message, $code);

        return $exception;
    }

    /**
     * 获取错误 Map
     *
     * @return array
     */
    protected static function getCodeMap() : array
    {
        return self::ERR_CODE_MAP;
    }

    /**
     * 根据 code 获取错误 message
     *
     * @param $code
     * @return string
     */
    private static function getErrMsg($code) : string
    {
        $codeMap = static::getCodeMap();

        if (!isset($codeMap[$code])) {
            return "未定义的错误码[{$code}]";
        }

        return $codeMap[$code];
    }
}
