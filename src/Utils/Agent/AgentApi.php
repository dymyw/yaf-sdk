<?php

namespace Dymyw\Yaf\Utils\Agent;

use Dymyw\Yaf\Utils\Common;
use Dymyw\Yaf\Utils\ResultUtil;
use Requests;

/**
 * Class AgentApi
 * @package Dymyw\Yaf\Utils\Agent
 */
class AgentApi
{
    /**
     * 超时时间
     */
    const TIMEOUT = 10;

    /**
     * 额外参数
     *
     * @var array
     */
    static $options = [];

    /**
     * 请求 Api
     *
     * @param mixed ...$apis
     * @return float
     * @throws \Requests_Exception
     */
    public static function request(...$apis)
    {
        $pre = $requests = $responses = [];

        /** @var AbstractApi $api */
        foreach ($apis as $api) {
            $request = $api->getRequest();

            $pre[]      = $api;
            $requests[] = $request;
        }

        $isOneRequest = count($requests) == 1;
        $start = Common::getMicroTime();
        if ($isOneRequest) {
            $responses[] = Requests::request(
                $request['url'],
                $request['header'] ?? [],
                $request['data'] ?? [],
                $request['type'] ?? Requests::GET,
                self::getOptions()
            );
        } else {
            $responses = Requests::request_multiple($requests, self::getOptions());
        }
        $cost = Common::getMicroTime() - $start;

        foreach ($responses as $key => $response) {
            if (!is_object($response)) {
                $response = ResultUtil::success($response);
            }
            /** @var AbstractApi[]  $pre */
            $pre[$key]->setResponse($response);
            $pre[$key]->afterRequest();
        }

        return $cost;
    }

    /**
     * 设置超时时间
     *
     * @param int $timeout
     */
    public static function setTimeout(int $timeout = self::TIMEOUT)
    {
        self::$options['timeout'] = $timeout;
    }

    /**
     * 获取额外参数
     *
     * @return array
     */
    public static function getOptions()
    {
        return self::$options;
    }
}
