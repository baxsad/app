<?php

namespace Buff\lib\config;
use APP;

class Config
{
	private static $instance = [];
    private $name;
    private $caches = [];

	public static function instance($name)
    {
        if (!isset(self::$instance[$name])){
            self::$instance[$name] = new self($name);
        }
        return self::$instance[$name];
    }

    private function __construct($name)
    {
        $this->name = $name;
    }

    private function loadConfig($module)
    {
        if (!isset($this->caches[$module])) {
            $path = APP::$base_root . DS . 'config' . DS . $module . '.php';
            $config = is_readable($path) ? require($path) : [];
            $this->caches[$module] = $config;
        }

        return $this->caches[$module];
    }

    public function get($key, $module='main')
    {
        $config = $this->loadConfig($module);
        if (isset($config[$key])) {
            return $config[$key];
        } else {
            return null;
        }
    }
}