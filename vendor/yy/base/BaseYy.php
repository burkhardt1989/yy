<?php

namespace yy\base;

use yy;
use yy\base\Object;
use yy\di\Container;

class BaseYy extends Object
{
	public $version = '0.01';

	public static $container;
	public static $components;

	public function __construct()
	{
	}

	public static function createApplication($class, $config)
	{
		$application = new $class($config);
		Yy::$app = $application;

		return $application;
	}
}