<?php

namespace Dymyw\Yaf\Utils\Agent;

use Dymyw\Yaf\Response\Exception;

/**
 * Class AbstractApi
 * @package Dymyw\Yaf\Utils\Agent
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * 接口请求完成后的回调函数
     *
     * @var null|callable
     */
    public $afterRequest = null;

    /**
     * 请求地址
     *
     * @var string
     */
    protected $url = '';

    /**
     * 请求参数
     *
     * @var array
     */
    protected $params = [];

    /**
     * 请求失败的默认结果
     *
     * @var mixed
     */
    protected $default;

    /**
     * 接口响应结果
     *
     * @var mixed
     */
    private $response;

    /**
     * 获取请求
     *
     * @return array
     * @example [
     *              'url'       => '',      // 必须
     *              'header'    => [],      // 非必须
     *              'data'      => [],      // 非必须
     *              'type'      => 'get',   // 非必须，默认 get
     *          ]
     */
    abstract public function getRequest();

    /**
     * 设置请求地址
     *
     * @param string $url
     * @return $this|mixed
     */
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * 设置请求参数
     *
     * @param $key
     * @param $value
     * @return $this|mixed
     */
    public function setParams($key, $value)
    {
        if (is_array($key)) {
            $this->params = array_merge($this->params, $key);
        } else {
            if (is_array(json_decode($key, true))) {
                $this->params = array_merge($this->params, $key);
            } else {
                $this->params[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * 获取接口请求失败的默认值
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * 设置接口响应结果
     *
     * @param $response
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * 获取接口响应结果
     *
     * @return mixed
     * @throws Exception
     */
    public function getResponse()
    {
        $response = json_decode($this->response->body, true);

        if ($response === false || $response === null) {
            throw Exception::error(sprintf('请求数据解析失败：%s', json_encode($this->getRequest())), 900000);
        }

        return $response;
    }

    /**
     * 接口请求后续操作
     */
    public function afterRequest()
    {
        if ($this->afterRequest) {
            call_user_func($this->afterRequest, $this);
        }
    }
}
