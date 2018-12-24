<?php

namespace Dymyw\Yaf\Utils;

use Yaf\Registry;

/**
 * Class Logger
 * @package Dymyw\Yaf
 */
class Logger
{
    /**
     * @var null|Logger
     */
    private static $instance = null;

    /**
     * @var string
     */
    private static $logPath;

    /**
     * Logger constructor.
     * @param $logPath
     */
    public function __construct($logPath)
    {
        self::$logPath = $logPath;
    }

    /**
     * 获取单例
     *
     * @return Logger
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            $config = Registry::get('config');
            self::$instance = new self($config->log->path);
        }

        return self::$instance;
    }

    /**
     * 打印调试日志
     *
     * @param $message
     * @param array $data
     */
    public function debug($message, array $data = [])
    {
        $this->general('debug', $message, 0, $data);
    }

    /**
     * 打印错误日志
     *
     * @param $message
     * @param int $code
     * @param array $data
     */
    public function error($message, $code = 0, array $data = [])
    {
        $this->general('error', $message, $code, $data);
    }

    /**
     * 根据业务打印日志
     *
     * @param $logType
     * @param $message
     * @param int $code
     * @param array $data
     */
    public function general($logType, $message, $code = 0, array $data = [])
    {
        $content = [
            'time'  => date("Y-m-d H:i:s"),
            'type'  => $logType,
            'code'  => $code,
            'msg'   => $message,
            'data'  => $data,
        ];
        $logContent = json_encode($content, JSON_UNESCAPED_UNICODE) . PHP_EOL;

        error_log($logContent, 3, self::$logPath . $logType . '.' . date("Ymd"));
    }
}
