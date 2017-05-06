<?php
namespace yy\di;

use ReflectionClass;
use yy\base\Object;

class Container extends Object
{
	/**
	 * class Request 
	 * {
	 * 		public $obj;
	 * 		public $color;
	 *
	 * 		public function __construct(\yy\base\Object $obj, $color1 = 'red')
	 * 		{
	 * 			$this->obj = $obj;
	 * 			$this->color = $color1;
	 * 		}
	 * }
	 *
	 * 配置文件
	 * 	'request' => [
	 * 		'class' => '\yy\web\Request',
	 * 	],
	 *  'object' => [
	 * 		'class' => '\yy\base\Object',
	 * 	],
	 * 使用方法
	 * Yy::$components->get('request', ['color1' => 'white']);
	 *
	 * 返回值
	 * yy\web\Request Object
	 * 	(
	 * 		[obj:yy\web\Request:private] => yy\base\Object Object()
	 * 		[color:yy\web\Request:private] => white
	 * 	)
	 * 	
	 */

	// 单例模式
	private $_singletons = [];

	// di绑定
	private $_bindings = [];

	// 依赖
	private $_dependencies = [];
	
	// 反射缓存
	private $_reflections = [];

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
	public function get($name, $params = [])
	{
		// 单例模式直接返回
		if (isset($this->_singletons[$name])) {
			return $this->_singletons[$name];
		}
		// 合并_bindings参数
		$dependency = $this->getDependencies($name);
		$params = $this->mergeParam($dependency, $this->_bindings[$name], $params);
		// 存在需要实例化的依赖
		if (is_array($dependency) && isset($dependency['class']) && $dependency['class'] != $this->getClassName($name)) {
			return $this->get($dependency['class'], $params);
		}
		return $this->build($name, $params);
	}

	// 创建返回数据
	protected function build($name, $params)
	{
		// $binding为空，返回以$name为类,$param为参数的实例
		// $binding为数组且没有class的key
		// $binding为数组且存在class的key
		$binding = $this->_bindings[$name];
		if (empty($binding) || is_array($binding)) {
			$reflection = $this->getReflection($name);
			return $reflection->newInstanceArgs($params);
		}
		// 如果依赖为单例
		elseif (is_object($binding)) {
			return $binding;
		}
		// 如果为可执行函数
		elseif (is_callable($binding)) {
			return call_user_func($binding, $params);
		}
		throw new Exception("Cann't build " . $name);
	}

	// 获得依赖
	public function getDependencies($name)
	{
		// 进行过反射的类直接返回依赖
		if (isset($this->_reflections[$name])) {
			return $this->_dependencies[$name];
		}

		// 反射解析类
		$reflection = $this->getReflection($name);
		$constructor = $reflection->getConstructor();
		if ($constructor !== null) {
			// 解析构造函数,并以构造函数参数名为key记录依赖
			foreach ($constructor->getParameters() as $param) {
				// 普通参数赋值默认值
				// 类参数实现类
				if ($param->isDefaultValueAvailable()) {
					$this->_dependencies[$name][$param->name] = $param->getDefaultValue();
				} else if ($c = $param->getClass()) {
					$name = $this->getKeyName($c->getName());
					$this->_dependencies[$name][$param->name] = $this->get($name);
				}
			}
		}
		return isset($this->_dependencies[$name]) ? $this->_dependencies[$name] : [];
	}

	// 获取反射
	public function getReflection($name)
	{
		if (isset($this->_reflections[$name])) {
			return $this->_reflections[$name];
		}
		$this->_reflections[$name] = new ReflectionClass($this->getClassName($name));
		return $this->_reflections[$name];
	}

	// 获取name对应的class
	public function getClassName($name)
	{
		$binding = $this->_bindings[$name];
		return isset($binding['class']) ? $binding['class'] : $name;
	}

	// 获取class对应的name
	public function getKeyName($class)
	{
		$name = '';
		// 查找class对应的name
		foreach ($this->_bindings as $k => $v) {
			if (isset($v['class']) && $v['class'] == $class) {
				$name = $k;
				break;
			}
		}
		// 如果没有绑定,新建一个绑定
		if (!$name) {
			$name = $class;
			$this->set($name, ['class' => $class]);
		}
		return $name;
	}

	// 合并参数,使用后面的覆盖$first中存在的参数
	public function mergeParam()
	{
		$args = func_get_args();
		$frist = array_shift($args);
		foreach ($args as $arg) {
			foreach ($frist as $k => $v) {
				if (isset($arg[$k])) {
					$frist[$k] = $arg[$k];
				}
			}
		}
		return $frist;
	}

}