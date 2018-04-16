<?php

namespace Buff\classes\utils;

use Firebase\JWT\JWT;
use Buff\classes\utils\Environment;

class Auth
{
	public static function create($uid,$account)
	{
        $now = strtotime(date("Y-m-d H:i:s"));
        $future = strtotime((new \DateTime('+1 day'))->format('Y-m-d H:i:s'));
        $payload   = [
        	"iss"     => "buff",
            "iat"     => $now,
            "exp"     => $future,
            "aud"     => "oye.moe",
            "sub"     => "everyone",
            "jti"     => 'qwq',
            "uid"     => $uid,
            "account" => $account,
        ];
        $token = JWT::encode($payload, Environment::$jwtSecretKey, Environment::$jwtAlgorithm);
        $data["token"] = $token;
        $data["expires"] = $future;

        return $data;
	}

    private static function RandomToken($length = 32)
    {
        if(!isset($length) || intval($length) <= 8 ){
            $length = 32;
        }
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length));
        }
        if (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
        } 
        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }
    }

    private static function Salt()
    {
        return substr(strtr(base64_encode(hex2bin(RandomToken(32))), '+', '.'), 0, 44);
    }

}