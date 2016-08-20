<?php
namespace yy;

use yy\base\Object;

class BaseYy
{
	public static $classMap = [];

	public static $app;

	public static $container;

	public function __construct($config = [])
	{
		foreach ($config as $name => $value) {
            $object->$name = $value;
        }
	}

	public static function autoload($className)
	{
		if (isset(static::$classMap[$className])) {
			$classFile = static::$classMap[$className];
		}
		echo $className;

		include($classFile);

        if (!class_exists($className, false) && !interface_exists($className, false)) {
        	throw new Exception("Unable to find '$className' in file: $classFile. Namespace missing?", 1);
        }
	}


}