<?php

namespace Dymyw\Yaf\Controller;

use Dymyw\Yaf\Request\AbstractRequest;
use Dymyw\Yaf\Utils\ResultUtil;
use Yaf\Controller_Abstract;
use Yaf\Dispatcher;

class AbstractController extends Controller_Abstract
{
    public function init()
    {
        Dispatcher::getInstance()->disableView();
    }

    public function getRequest()
    {
        return new AbstractRequest();
    }

    /**
     * 返回成功结果
     *
     * @param mixed $data
     * @return string
     */
    public function success($data = null)
    {
        return $this->getResponse()->setBody(ResultUtil::success($data));
    }

    /**
     * 返回失败结果
     *
     * @param string $msg
     * @param int $code
     * @return string
     */
    public function failed($msg, $code = -1)
    {
        return $this->getResponse()->setBody(ResultUtil::failed($msg, $code));
    }

    /**
     * 返回成功结果
     *
     * @param array $result
     * @return string
     */
    public function response($result)
    {
        return $this->success($result);
    }
}
