<?php

namespace Dymyw\Yaf\Utils;

/**
 * Class ResultUtil
 * @package Dymyw\Yaf\Utils
 */
class ResultUtil
{
    /**
     * 错误码
     */
    const ERROR_NO  = 'err_no';

    /**
     * 错误信息
     */
    const ERROR_MSG = 'err_msg';

    /**
     * 数据
     */
    const DATA      = 'data';

    /**
     * 返回成功结果
     *
     * @param $data
     * @return string
     */
    public static function success($data) : string
    {
        $result = [
            self::ERROR_NO  => 0,
            self::ERROR_MSG => 'success',
            self::DATA      => $data,
            'timestamp'     => time(),
        ];

        return self::toJson($result);
    }

    /**
     * 返回失败结果
     *
     * @param string $msg
     * @param int $code
     * @param array $data
     * @return string
     */
    public static function failed(string $msg, int $code = -1, array $data = []) : string
    {
        $result = [
            self::ERROR_NO  => $code,
            self::ERROR_MSG => $msg,
            self::DATA      => $data,
            'timestamp'     => time(),
        ];

        return self::toJson($result);
    }

    /**
     * 返回异常结果
     *
     * @param \Exception $exception
     * @return string
     */
    public static function exception(\Exception $exception) : string
    {
        $code = $exception->getCode();
        if (!$code) {
            $code = -1;
        }

        $result = [
            self::ERROR_NO  => $code,
            self::ERROR_MSG => $exception->getMessage(),
            self::DATA      => [],
            'timestamp'     => time(),
        ];

        return self::toJson($result);
    }

    /**
     * Array 转 Json 数据
     *
     * @param array $result
     * @return string
     */
    private static function toJson(array $result) : string
    {
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
