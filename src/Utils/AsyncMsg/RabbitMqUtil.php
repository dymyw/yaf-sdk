<?php

namespace Dymyw\Yaf\Utils\AsyncMsg;

use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPLazyConnection;
use Thumper\ConnectionRegistry;
use Thumper\Producer;
use Yaf\Registry;

/**
 * Class RabbitMqUtil
 * @package Dymyw\Yaf\Utils\AsyncMsg
 */
class RabbitMqUtil
{
    /**
     * @var RabbitMqUtil
     */
    protected static $instance;

    /**
     * @var AbstractConnection[]
     */
    private $conn = [];

    /**
     * 默认连接名称
     *
     * @var string
     */
    private $defaultConnKey     = 'default';

    /**
     * connection options
     */
    private $vHost              = '/';
    private $insist             = false;
    private $loginMethod        = 'AMQPLAIN';
    private $loginResponse      = null;
    private $locale             = 'en_US';
    private $connectionTimeout  = 3.0;
    private $readWriteTimeout   = 180;
    private $context            = null;
    private $keepAlive          = false;
    private $heartbeat          = 60;

    /**
     * @var ConnectionRegistry
     */
    private $register = null;

    /**
     * @var null|Producer
     */
    private $producer = null;

    /**
     * RabbitMqUtil constructor.
     */
    public function __construct()
    {
        $config         = Registry::get('config');
        $rabbitMqConfig = $config->amqp;

        $this->setConnection(
            $this->defaultConnKey,
            $rabbitMqConfig->host,
            $rabbitMqConfig->port,
            $rabbitMqConfig->username,
            $rabbitMqConfig->password
        );
        $this->register = new ConnectionRegistry($this->conn, $this->defaultConnKey);
    }

    /**
     * 获取 RabbitMqUtil 单例
     *
     * @return RabbitMqUtil
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof RabbitMqUtil) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $key
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @return $this
     */
    public function setConnection($key, $host, $port, $username, $password)
    {
        if (!key_exists($key, $this->conn)) {
            $conn = new AMQPLazyConnection(
                $host,
                $port,
                $username,
                $password,
                $this->vHost,
                $this->insist,
                $this->loginMethod,
                $this->loginResponse,
                $this->locale,
                $this->connectionTimeout,
                $this->readWriteTimeout,
                $this->context,
                $this->keepAlive,
                $this->heartbeat
            );

            $this->conn[$key] = $conn;

            if ($key != $this->defaultConnKey) {
                $this->register->addConnection($key, $conn);
            }
        }

        return $this;
    }

    /**
     * @param $name
     * @return Producer
     */
    public function getProducer($name)
    {
        if (!$this->producer) {
            $this->producer = new Producer($this->register->getConnection($name));
        }

        return $this->producer;
    }
}
