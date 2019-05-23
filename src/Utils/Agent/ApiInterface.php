<?php

namespace Dymyw\Yaf\Utils\Agent;

/**
 * Interface ApiInterface
 * @package Dymyw\Yaf\Utils\Agent
 */
interface ApiInterface
{
    /**
     * 设置请求地址
     *
     * @param string $url
     * @return mixed
     */
    public function setUrl(string $url);

    /**
     * 设置请求参数
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setParams($key, $value);

    /**
     * 获取接口请求失败的默认值
     *
     * @return mixed
     */
    public function getDefault();
}
