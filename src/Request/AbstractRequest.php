<?php

namespace Dymyw\Yaf\Request;

use Yaf\Dispatcher;
use Yaf\Request_Abstract;

/**
 * Class AbstractRequest
 * @package Dymyw\Yaf\Request
 */
class AbstractRequest implements RequestInterface
{
    /**
     * 请求参数
     *
     * @var array
     */
    private $params = [];

    /**
     * 请求 Request 对象
     *
     * @var Request_Abstract
     */
    private $requestObj;

    /**
     * AbstractRequest constructor.
     * @param Request_Abstract $requestObj
     */
    public function __construct(Request_Abstract $requestObj)
    {
        $this->requestObj = $requestObj;
        $this->initParams();
    }

    /**
     * 初始化请求参数
     */
    public function initParams()
    {
        $params = Dispatcher::getInstance()->getRequest()->getParams();
        if (!empty($params)) {
            foreach ($params as $name => $value) {
                $_GET[$name] = $value;
            }
        }

        // raw
        $input = json_decode(file_get_contents('php://input'), true);
        $input = is_array($input) ? $input : [];

        $this->params = array_merge($_GET, $_POST, $input);
    }

    /**
     * 获取请求方式
     *
     * @return string
     */
    public function getMethod() : string
    {
        return $this->requestObj->getMethod();
    }

    /**
     * 过滤请求参数
     *
     * @param array $filter
     * @return array
     */
    public function getParams(array $filter = []) : array
    {
        $params = [];

        foreach ($this->params as $name => $value) {
            if ($filter && !in_array($name, $filter)) {
                continue;
            }

            if (null !== $value) {
                $params[$name] = $value;
            }
        }

        return $params;
    }

    /**
     * 获取请求参数
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParam(string $name, $default = null)
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->getParam($name);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->requestObj->{$name}($arguments);
    }
}
