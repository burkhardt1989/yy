<?php

namespace yy\base;

use yy;

class Object
{
	public function __construct()
	{
		$this->init();
	}

	public function init();

	public function __get($name)
	{
		$getter = 'get' . $name;
		if (method_exists($this, $getter)) {
			return $this->$getter();
		}
		throw new Exception("Getting unknown property: " . get_class($this) . '::' . $name, 1);
	}

	public function __set($name)
	{
		$setter = 'set' . $name;
		if (method_exists($this, $setter)) {
			return $this->$setter();
		}
		throw new Exception("Setting unknown property: " . get_class($this) . '::' . $name, 1);		
	}
}