<?php

namespace Dymyw\Yaf\Plugin;

use Yaf\Dispatcher;
use Yaf\Plugin_Abstract;
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;

/**
 * Class FormRequest
 * @package Dymyw\Yaf\Request
 */
class FormRequest extends Plugin_Abstract
{
    /**
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     * @return mixed|void
     */
    public function preDispatch(Request_Abstract $request, Response_Abstract $response)
    {
        $appDirect = Dispatcher::getInstance()->getApplication()->getAppDirectory();
        $formRequestFilePath = $appDirect
            . ('Index' != $request->module ? "/modules/{$request->module}" : '')
            . "/requests/{$request->controller}/" . ucfirst($request->action) . '.php';

        if (@file_exists($formRequestFilePath)) {
            require_once $formRequestFilePath;

            $className = implode('\\', [
                $request->module,
                'Request',
                $request->controller,
                ucfirst($request->action),
            ]);
            if (@class_exists($className)) {
                new $className($request);
            }
        }
    }
}
