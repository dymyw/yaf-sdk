<?php

namespace Dymyw\Yaf\Controller;

use Yaf\Action_Abstract;

abstract class AbstractAction extends Action_Abstract
{
    abstract public function execute();

    public function getRequest()
    {
        return $this->getController()->getRequest();
    }
}
