<?php

use Yaf\Controller_Abstract;

abstract class BaseController extends Controller_Abstract
{
    /**
     * 成功返回
     * @param $data
     */
    protected function success($data){
        $this->getResponse()->setBody(json_encode([
            'code' => 200,
            'msg' => 'success',
            'data' => $data
        ]));
    }

    /**
     * 失败返回
     * @param string $msg
     * @param int $code
     */
    protected function error(string $msg, $code = 1){
        $this->getResponse()->setBody(json_encode([
            'code' => $code,
            'msg' => $msg
        ]));
    }
}