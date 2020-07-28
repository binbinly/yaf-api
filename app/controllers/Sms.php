<?php

use Yaf\Registry;

class SmsController extends BaseController
{
    /**
     * 发送验证码
     */
    public function sendAction(){
        $mobile = $this->getRequest()->getParam('mobile');
        if (!$mobile || strlen($mobile) != 11) {
            return $this->error('手机号非法');
        }
        $handler = Sms_Handler_Yun::getInstance(Registry::get('config')['sms']['yun']);
        $handler->setCallback([SmsController::class, 'callback']);

        $ret = $handler->send($mobile);
        if($ret === false) {
            $this->error($handler->getErr());
        } else {
            $this->success(['code' => $ret]);
        }
    }

    /**
     * 验证码发送成功回调
     * @param string $mobile 手机号
     * @param string $code 发送的验证码
     */
    public static function callback(string $mobile, string $code)
    {
        /** @var Predis\Client $redis */
        $redis = Registry::get('redis');
        $redis->setEx("sms_code:".$mobile, 600, $code);
    }
}