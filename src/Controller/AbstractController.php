<?php

namespace Dymyw\Yaf\Controller;

use Dymyw\Yaf\Request\AbstractRequest;
use Dymyw\Yaf\Utils\ResultUtil;
use Yaf\Controller_Abstract;
use Yaf\Request_Abstract;

/**
 * Class AbstractController
 * @package Dymyw\Yaf\Controller
 */
abstract class AbstractController extends Controller_Abstract
{
    /**
     * 返回 Request 对象
     *
     * @return AbstractRequest|Request_Abstract
     */
    public function getRequest()
    {
        $requestObj = parent::getRequest();
        return new AbstractRequest($requestObj);
    }

    /**
     * 返回成功结果
     *
     * @param mixed $data
     */
    public function success($data = null)
    {
        $data = ResultUtil::success($data);
        $this->renderJson($data);
    }

    /**
     * 返回失败结果
     *
     * @param string $msg
     * @param int $code
     */
    public function failed(string $msg, int $code = -1)
    {
        $data = ResultUtil::failed($msg, $code);
        $this->renderJson($data);
    }

    /**
     * 返回成功结果
     *
     * @param $result
     */
    public function response($result)
    {
        return $this->success($result);
    }

    /**
     * 获取分页参数
     *
     * @param int $defaultNum
     * @param int $defaultLimit
     * @return array
     */
    public function getPaginationParams($defaultNum = 1, $defaultLimit = 20)
    {
        $pageOptions = [
            'page_num'          => $defaultNum,
            'page_limit'        => $defaultLimit,
            'need_pagination'   => false,
        ];

        if (
            null != $this->getRequest()->getParam('page_num')
            && intval($this->getRequest()->getParam('page_num')) > 1
        ) {
            $pageOptions['page_num'] = intval($this->getRequest()->getParam('page_num'));
        }

        if (
            null != $this->getRequest()->getParam('page_limit')
            && intval($this->getRequest()->getParam('page_limit')) > 1
        ) {
            $pageOptions['page_limit'] = intval($this->getRequest()->getParam('page_limit'));
        }

        if ($this->getRequest()->getParam('need_pagination')) {
            $pageOptions['need_pagination'] = true;
        }

        return $pageOptions;
    }

    /**
     * @param string $jsonString
     */
    private function renderJson(string $jsonString)
    {
        $this->getResponse()->setHeader("Content-Type", "application/json");
        $this->getResponse()->setBody($jsonString);
    }
}
