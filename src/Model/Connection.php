<?php

namespace Dymyw\Yaf\Model;

use Medoo\Medoo;
use Yaf\Registry;

/**
 * Class Connection
 * @package Dymyw\Yaf\Model
 */
class Connection
{
    /**
     * 连接实例
     *
     * @var array
     */
    protected static $instances = [];

    /**
     * 获取实例
     *
     * @param string $db
     * @param null $options
     * @return mixed
     */
    public static function getInstance($db = 'default_db', $options = null)
    {
        if (!isset(self::$instances[$db])) {
            $config = Registry::get('config');

            if (is_null($options)) {
                $options = [
                    'database_type' => 'mysql',
                    'server'        => $config->database->host,
                    'port'          => $config->database->port,
                    'username'      => $config->database->username,
                    'password'      => $config->database->password,
                    'database_name' => $config->database->db,
                    'charset'       => $config->database->charset,
                ];
            }

            self::$instances[$db] = new Medoo($options);
        }

        return self::$instances[$db];
    }
}
