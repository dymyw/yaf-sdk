<?php

namespace Dymyw\Yaf\Request;

interface RequestInterface
{
    /**
     * 获取请求方式
     *
     * @return string
     */
    public function getMethod();

    /**
     * 过滤请求参数
     *
     * @param array $filter
     * @return array
     */
    public function getParams(array $filter = []);

    /**
     * 获取请求参数
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParam($name, $default = null);
}
