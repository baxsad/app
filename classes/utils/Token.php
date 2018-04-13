<?php

namespace Buff\classes\utils;

use Firebase\JWT\JWT;
use Buff\classes\utils\Environment;

class Token
{
	public static function create($uid)
	{
        $now = strtotime(date("Y-m-d H:i:s"));
        $future = strtotime((new \DateTime('+99 day'))->format('Y-m-d H:i:s'));
        $payload   = [
            "uid" => $uid,
            "iat" => $now,
            "exp" => $future
        ];
        $token = JWT::encode($payload, Environment::jwtSecretKey, "HS256");
        $data["token"] = $token;
        $data["expires"] = $future;

        return $data;
	}
}