<?php

namespace Dymyw\Yaf\Controller;

use Yaf\Action_Abstract;

/**
 * Class AbstractAction
 * @package Dymyw\Yaf\Controller
 * 
 * @method AbstractController getRequest()
 * @method AbstractController success($data = null) 返回成功结果
 * @method AbstractController failed(string $msg, int $code = -1) 返回失败结果
 * @method AbstractController response($result) 返回成功结果
 */
abstract class AbstractAction extends Action_Abstract
{
    abstract public function execute();

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->getController(), $name], $arguments);
    }
}
