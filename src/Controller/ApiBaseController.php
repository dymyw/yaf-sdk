<?php

namespace Dymyw\Yaf\Controller;

use Yaf\Dispatcher;

class ApiBaseController extends AbstractController
{
    /**
     * 控制器初始化
     */
    public function init()
    {
        Dispatcher::getInstance()->disableView();
    }
}
