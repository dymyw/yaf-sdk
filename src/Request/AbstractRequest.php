<?php

namespace Dymyw\Yaf\Request;

use Yaf\Dispatcher;
use Yaf\Request_Abstract;

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
     * AbstractRequest constructor
     */
    public function __construct()
    {
        $this->setRequest(Dispatcher::getInstance()->getRequest())
             ->initParams();
    }

    /**
     * 设置 Request 对象
     *
     * @param Request_Abstract $request
     * @return $this
     */
    public function setRequest(Request_Abstract $request)
    {
        $this->requestObj = $request;

        return $this;
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
    public function getMethod()
    {
        return $this->requestObj->getMethod();
    }

    /**
     * 过滤请求参数
     *
     * @param array $filter
     * @return array
     */
    public function getParams(array $filter = [])
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
    public function getParam($name, $default = null)
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getParam($name);
    }

    /**
     * @param string $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->requestObj->{$name}($arguments);
    }
}
