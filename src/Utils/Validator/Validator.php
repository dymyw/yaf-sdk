<?php

namespace Dymyw\Yaf\Utils\Validator;

use Dymyw\Yaf\Response\Exception;
use Dymyw\Yaf\Utils\ConstMap;

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
        if (!self::$instance instanceof \GUMP) {
            // 载入预设配置
            $langFile = require __DIR__ . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . 'zh_CN.php';
            \GUMP::set_field_names($langFile['field']);
            \GUMP::set_error_messages($langFile['error']);

            // 添加自定义验证规则
            self::addCustomValidator();

            self::$instance = new \GUMP();
        }

        // 设置自定义可读字段名
        if (!empty($fieldNames)) {
            \GUMP::set_field_names($fieldNames);
        }

        // 设置自定义错误
        if (!empty($errorMessage)) {
            \GUMP::set_error_messages($errorMessage);
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

    /**
     * 添加自定义验证规则
     *
     * @throws \Exception
     */
    protected static function addCustomValidator()
    {
        /**
         * 手机号验证
         *
         * @param $field 需要验证的字段名
         * @param $input 需要验证的数据集
         * @param @param 规则参数，如 mobile,1;2;3 中的 1;2;3
         */
        \GUMP::add_validator('mobile', function ($field, $input, $param = null) {
            return (bool) preg_match(ConstMap::REGEXP_MOBILE, $input[$field]);
        });

        /**
         * 列表验证，自带的 contains 有问题
         *
         * @example in,1 2 3
         */
        \GUMP::add_validator('in', function ($field, $input, $param = null) {
            return in_array($input[$field], explode(' ', $param));
        });
    }
}
