<?php

use Firebase\JWT\JWT;

class JwtHelper
{
    use Singleton;

    private $secret;

    private $sign = 'HS256';

    private function __construct($config)
    {
        $this->secret = $config['key'];
    }

    public function encode(array $userData, $expire = 7200){
        $now = time();
        $payload = array(
            "iat" => $now,    //发布时间
            "exp" => time() + $expire //到期时间
        );
        $payload = array_merge($payload, $userData);

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        return JWT::encode($payload, $this->secret);
    }

    public function decode($token){
        return (array) JWT::decode($token, $this->secret, array($this->sign));
    }
}