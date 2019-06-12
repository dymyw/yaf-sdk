<?php

namespace Dymyw\Yaf\Utils\AsyncMsg;

use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPLazyConnection;
use Thumper\ConnectionRegistry;
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
    protected static $instance  = null;

    /**
     * @var AbstractConnection[]
     */
    private $conn               = [];

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
     * @var string
     */
    private $defaultConnName    = 'default';

    /**
     * @var null|ConnectionRegistry
     */
    private $register           = null;

    /**
     * RabbitMqUtil constructor.
     */
    public function __construct()
    {
        $config     = Registry::get('config');
        $amqpConfig = $config->amqp;

        $this->setConnection(
            $this->defaultConnName,
            $amqpConfig->host,
            $amqpConfig->port,
            $amqpConfig->username,
            $amqpConfig->password
        );
        $this->register = new ConnectionRegistry($this->conn, $this->defaultConnName);
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
     * @param $name
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @return $this
     */
    public function setConnection($name, $host, $port, $username, $password)
    {
        if (!array_key_exists($name, $this->conn)) {
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

            $this->conn[$name] = $conn;

            if ($name != $this->defaultConnName) {
                $this->register->addConnection($name, $conn);
            }
        }

        return $this;
    }

    /**
     * @param null $name
     * @return AbstractConnection
     */
    public function getConnection($name = null)
    {
        return $this->register->getConnection($name);
    }
}
