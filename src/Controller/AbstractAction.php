<?php

namespace Dymyw\Yaf\Controller;

use Dymyw\Yaf\Request\AbstractRequest;
use Yaf\Action_Abstract;
use Yaf\Request_Abstract;

/**
 * Class AbstractAction
 * @package Dymyw\Yaf\Controller
 * 
 * @method AbstractRequest|Request_Abstract getRequest() 返回 Request 对象
 * @method void success(array $data = []) 返回成功结果
 * @method void failed(string $msg, int $code = -1) 返回失败结果
 * @method void response(array $result = []) 返回成功结果
 * @method array getPaginationParams($defaultNum = 1, $defaultLimit = 20) 获取分页参数
 */
abstract class AbstractAction extends Action_Abstract
{
    abstract public function execute();

    /**
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments = [])
    {
        return call_user_func_array([$this->getController(), $name], $arguments);
    }
}
