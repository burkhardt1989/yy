<?php

namespace yy\base;

use yy;
use yy\base\Object;
use yy\di\Container;

class BaseYy extends Object
{
	public $version = '0.01';

	public static $container;

	public static $serviceLocator;

	public function __construct($config = [])
	{
		$this->container = new Container();
		foreach ($config as $name => $value) {
			$this->name = $value;
		}
	}

	public static function createApplication($class, $config)
	{
		$application = new $class($config);
		Yy::$app = $application;

		return $application;
	}
}