<?php

use Yaf\Registry;

abstract class Sms_Adapter implements Sms_Interface
{
    use Singleton;

    protected $config;

    protected $callback;

    protected $err;

    /**
     * 频率限制
     * @var array
     */
    protected $rules = [
        [
            "cache_sms" => "sms_minute",
            "ttl" => 60,
            "count" => 1,
            'msg' => '一分钟限制一次'
        ],
        [
            "cache_sms" => "sms_hour",
            "ttl" => 3600,
            "count" => 10,
            'msg' => '一小时限制十次'
        ],
        [
            "cache_sms" => "sms_day",
            "ttl" => 86400,
            "count" => 15,
            'msg' => '一天限制十五次'
        ]
    ];

    //是否真实发送验证码
    protected $isReal = true;

    /**
     * @var Predis\Client
     */
    protected $cache;

    protected function __construct(array $config)
    {
        $this->config = $config;
        $this->cache = Registry::get('redis');

        isset($config['real_send']) && $this->isReal = $this->config['real_send'];
    }

    /**
     * @return mixed
     */
    public function getErr()
    {
        return $this->err;
    }

    /**
     * 生成验证码
     * @param int $length
     * @return bool|string
     */
    public function createCode($length = 4)
    {
        return Random::number($length);
    }

    /**
     * 绑定回调
     * @param $callback
     */
    public function setCallback($callback): void
    {
        $this->callback = $callback;
    }

    /**
     * 发送验证码
     * @param string $mobile
     * @param $key
     * @return bool | string
     */
    public function send(string $mobile, $key = 0)
    {
        $ret = true;
        $code = $this->createCode();
        if ($this->isReal) {
            if (!$this->checkRules($mobile)) {
                return false;
            }
            $ret = $this->realSend($mobile, $code, $key);

            $ret && $this->execRules($mobile);
        }
        $ret && call_user_func($this->callback, $mobile, $code);
        return $ret ? $code : false;
    }

    /**
     * 发送成功执行规则
     * @param $mobile
     */
    protected function execRules($mobile)
    {
        if (!$this->rules) return;
        foreach ($this->rules as $rule) {
            $this->cache->incr($rule['cache_sms'] . $mobile);
            $this->cache->expire($rule['cache_sms'] . $mobile, $rule['ttl']);
        }
    }

    /**
     * 验证规则
     * @param $mobile
     * @return bool
     */
    protected function checkRules($mobile)
    {
        if (!$this->rules) return true;
        foreach ($this->rules as $rule) {
            if ($this->cache->get($rule['cache_sms'] . $mobile) >= $rule['count']) {
                $this->err = $rule['msg'];
                return false;
            }
        }
        return true;
    }
}
