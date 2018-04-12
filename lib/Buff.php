<?php

namespace Buff;

use Buff\lib\config\Config;

/**
* 
*/
class Buff
{
    public static $base;
    public static $base_root;
    public static $extends_root;

    public static function registry()
    {
    	self::define();
    	self::$base = new self();
    	self::$base_root = dirname(__DIR__);
    	self::$extends_root = self::$base_root.DS."extends";
    	self::init();
    }

    private static function define()
    {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);

        defined('ENV_DEV') or define('ENV_DEV', SYS_ENV === 'dev');
        defined('ENV_PRE') or define('ENV_PRE', SYS_ENV === 'pre');
        defined('ENV_PUB') or define('ENV_PUB', SYS_ENV === 'pub');
    }

    private static function init(){}

    public function __get($name)
    {
        switch ($name){
            case 'config':
                return Config::instance($name);

            default:
                return null;
        }
    }
}