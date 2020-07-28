<?php

use Yunpian\Sdk\YunpianClient;

/**
 * 云片
 * https://www.yunpian.com
 * Class SmsYunPian
 * @package App\Common\Sms\Handler
 */
class Sms_Handler_Yun extends Sms_Adapter
{
    protected $client = null;

    protected function __construct(array $config)
    {
        $this->client = YunpianClient::create($config['api_key']);
        parent::__construct($config);
    }

    /**
     * @param string $mobile
     * @param string $code
     * @param $key
     * @return bool
     */
    public function realSend(string $mobile, string $code, $key = 0) : bool
    {
        $param = [YunpianClient::MOBILE => $mobile,
            YunpianClient::TEXT => str_replace('#code#', $code, $this->config['tpl'])];
        $r = $this->client->sms()->single_send($param);

        if ($r->isSucc()) {
            return true;
        }
        $this->err = $r->code() . ':' . $r->exception();
        return false;
    }
}
