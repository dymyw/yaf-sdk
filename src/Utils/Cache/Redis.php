<?php

namespace Dymyw\Yaf\Utils\Cache;

use Yaf\Registry;

/**
 * Class Redis
 * @package Dymyw\Yaf\Utils\Cache
 */
class Redis
{
    /**
     * @var Redis
     */
    protected static $instance;

    /**
     * @var \Redis
     */
    protected $redis;

    /**
     * Redis constructor.
     */
    public function __construct()
    {
        $config         = Registry::get('config');
        $redisConfig    = $config->redis;
        $this->redis    = new \Redis();

        $this->redis->connect($redisConfig->host, $redisConfig->port, $redisConfig->timeout);
        if (!empty($redisConfig->auth)) {
            $this->redis->auth($redisConfig->auth);
        }

        $dbIndex = is_numeric($redisConfig->dbIndex) ? $redisConfig->dbIndex : 0;
        $this->redis->select($dbIndex);
    }

    /**
     * 获取 Redis 单例
     *
     * @return \Redis
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof Redis) {
            self::$instance = new self();
        }

        return self::$instance->redis;
    }
}
