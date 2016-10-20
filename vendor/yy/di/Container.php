<?php

namespace yy\di;

use yy\base\Object;

class Container extends Object
{
	// 单例模式
	private $_singletons = [];

	// di绑定
	private $_bindings = [];

	// 依赖
	private $_dependencies = [];

	// 绑定
	public function set($name, $dependency = [])
	{
		$this->_bindings[$name] = $dependency;
		unset($this->_singletons[$name]);
	}

	// 绑定单例
	public function setSingleton($name, $dependency = [])
	{
		$this->_bindings[$name] = $dependency;
		$this->_singletons[$name] = null;
	}

	// 获得实例
	// 单例模式直接返回
	public function get($name, $params= [])
	{
		if (isset($this->_singletons[$name])) {
			return $this->_singletons[$name];
		} else {
			// 当依赖还存在依赖时
			$dependency = $this->_dependencies[$name];
			if (is_array($dependency) && isset($dependency['class']) && $dependency['class'] != $name) {
				return $this->get($dependency['class'], $params);
			}
			return $this->build($name, $params);
		}
	}

	// 创建返回数据
	protected function build($name, $params)
	{

		// $dependency为空，返回以$name为类,$param为参数的实例
		// $dependency为数组且没有class的key
		// $dependency为数组且存在class的key
		if (empty($dependency) || is_array($dependency)) {
			$class = isset($dependency['class']) ? $dependency['class'] : $name;
			$reflection = new ReflectionClass($name);
			$constructor = $reflection->getConstructor();
			if ($constructor) {
				foreach ($constructor->getParameters() as $parameter) {
					if ($parameter->isDefaultValueAvailable()) {
						$dependency[] = $parameter->getDefaultValue();
					}
				}
			}
			return $reflection->newInstanceArgs($dependency);
		}
		// 如果依赖为单例
		elseif (is_object($dependency)) {
			return $dependency;
		}
		// 如果为可执行函数
		elseif (is_callable($dependency)) {
			return call_user_func($dependency, $params);
		}
		throw new Exception("Cann't build " . $name);
	}


}