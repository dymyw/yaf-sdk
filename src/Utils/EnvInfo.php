<?php

namespace Dymyw\Yaf\Utils;

/**
 * Class EnvInfo
 * @package Dymyw\Yaf\Utils
 */
class EnvInfo
{
    /**
     * 主机名称
     *
     * @var string
     */
    protected static $hostName  = '';

    /**
     * 唯一请求ID
     *
     * @var string
     */
    protected static $requestId = '';

    /**
     * 是否 cli 请求
     *
     * @var bool
     */
    protected static $isCli = false;

    /**
     * 获取主机名称
     *
     * @return string
     */
    public static function getHostName()
    {
        if (!self::$hostName) {
            if (!empty($_SERVER['HOSTNAME'])) {
                $hostName = $_SERVER['HOSTNAME'];
            } elseif ($hostName = gethostname()) {
                // gethostname
            } elseif ($hostName = php_uname('n')) {
                // php_uname
            }

            self::$hostName = $hostName ?: 'unknown_hostname';
        }

        return self::$hostName;
    }

    /**
     * 获取主机IP
     *
     * @return string
     */
    public static function getHostIp()
    {
        return $_SERVER['SERVER_ADDR'] ?? '';
    }

    /**
     * 获取唯一请求ID
     *
     * @return string
     */
    public static function getRequestId()
    {
        if (!self::$requestId) {
            $requestId = '';

            if (!empty($_REQUEST['request_id'])) {
                $requestId = $_REQUEST['request_id'];
            } elseif (!empty($_SERVER['X_REQUEST_ID'])) {
                $requestId = $_SERVER['X_REQUEST_ID'];
            }

            self::$requestId = $requestId ?: md5(uniqid('request_id', true) . mt_rand(10000,99999));
        }

        return self::$requestId;
    }

    /**
     * 获取请求地址
     *
     * @return string
     */
    public static function getRequestUri()
    {
        if (self::isCliType()) {
            return $_SERVER['argv'][1] ?? '/';
        } else {
            return explode('?', $_SERVER['REQUEST_URI'])[0];
        }
    }

    /**
     * 获取请求方式
     *
     * @return string
     */
    public static function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'cli';
    }

    /**
     * 判断是否 cli 请求
     *
     * @return bool
     */
    public static function isCliType()
    {
        if (is_null(self::$isCli)) {
            self::$isCli = stripos(php_sapi_name(), 'cli') !== false ? true : false;
        }
        return self::$isCli;
    }
}
