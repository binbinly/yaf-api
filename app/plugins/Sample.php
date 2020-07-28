<?php

use Yaf\Request_Abstract;
use Yaf\Response_Abstract;
use Yaf\Plugin_Abstract;
use Yaf\Registry;

class SamplePlugin extends Plugin_Abstract
{

    public function routerStartup(Request_Abstract $request, Response_Abstract $response)
    {

    }

    public function routerShutdown(Request_Abstract $request, Response_Abstract $response)
    {
        $token = $request->Get('token');
        if ($token) {
            $data = JwtHelper::getInstance(Registry::get('config')['jwt'])->decode($token);
            if (isset($data['data'])) {
                foreach ($data['data'] as $key => $value) {
                    $request->setParam($key, $value);
                }
                return true;
            }
        }
    }

    public function dispatchLoopStartup(Request_Abstract $request, Response_Abstract $response)
    {

    }

    public function preDispatch(Request_Abstract $request, Response_Abstract $response)
    {

    }

    public function postDispatch(Request_Abstract $request, Response_Abstract $response)
    {

    }

    public function dispatchLoopShutdown(Request_Abstract $request, Response_Abstract $response)
    {
        $response->setHeader('Content-Type', 'application/json; charset=utf-8');
    }
}
