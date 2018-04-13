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
}