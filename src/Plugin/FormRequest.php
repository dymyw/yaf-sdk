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

        // list is a keyword
        $actionName = $request->action;
        if ($actionName == 'list') {
            $actionName = 'ListRequest';
        }

        $formRequestFilePath = $appDirect
            . ('Index' != $request->module ? "/modules/{$request->module}" : '')
            . "/requests/{$request->controller}/" . ucfirst($actionName) . '.php';

        if (@file_exists($formRequestFilePath)) {
            require_once $formRequestFilePath;

            $className = implode('\\', [
                $request->module,
                'Request',
                $request->controller,
                ucfirst($actionName),
            ]);
            if (@class_exists($className)) {
                new $className($request);
            }
        }
    }
}
