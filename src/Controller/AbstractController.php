<?php

namespace Dymyw\Yaf\Controller;

use Yaf\Controller_Abstract;
use Yaf\Dispatcher;

class AbstractController extends Controller_Abstract
{
    public function init()
    {
        Dispatcher::getInstance()->disableView();
    }
}
