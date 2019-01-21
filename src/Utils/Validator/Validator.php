<?php

namespace Dymyw\Yaf\Utils\Validator;

use Dymyw\Yaf\Response\Exception;

/**
 * Class Validator
 * @package Dymyw\Yaf\Utils\Validator
 */
class Validator
{
    /**
     * GUMP 实例
     *
     * @var null|\GUMP
     */
    protected static $instance = null;

    /**
     * 获取 GUMP 实例
     *
     * @param array $fieldNames 自定义可读字段名
     * @param array $errorMessage 自定义错误
     * @return \GUMP|null
     * @throws \Exception
     */
    protected static function getInstance($fieldNames = [], $errorMessage = [])
    {
        $langFile = require __DIR__ . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . 'zh_CN.php';

        // 设置自定义可读字段名
        \GUMP::set_field_names($langFile['field']);
        if (!empty($fieldNames)) {
            \GUMP::set_field_names($fieldNames);
        }

        // 设置自定义错误
        \GUMP::set_error_messages($langFile['error']);
        if (!empty($errorMessage)) {
            \GUMP::set_error_messages($errorMessage);
        }

        if (!self::$instance instanceof \GUMP) {
            self::$instance = new \GUMP();
        }

        return self::$instance;
    }

    /**
     * 验证数据
     *
     * @param $data
     * @param $rules
     * @param array $fieldNames
     * @param array $errorMessage
     * @throws Exception
     */
    public static function validate($data, $rules, $fieldNames = [], $errorMessage = [])
    {
        $gump = self::getInstance($fieldNames, $errorMessage);

        // set the validation rules
        $gump->validation_rules($rules);

        // run
        $validated = $gump->run($data);

        if (false === $validated) {
            throw Exception::error(Exception::ERR_PARAMS, '：' . self::getFirstError($gump->get_errors_array()));
        }
    }

    /**
     * 返回第一条错误信息
     *
     * @param array $errorArray
     * @return mixed|string
     */
    protected static function getFirstError($errorArray = [])
    {
        foreach ($errorArray as $param => $errorMessage) {
            return $errorMessage;
        }

        return '';
    }
}
