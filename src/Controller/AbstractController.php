<?php

namespace Dymyw\Yaf\Controller;

use Dymyw\Yaf\Request\AbstractRequest;
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
}
