<?php
namespace yy\base;

use Yy;
use yy\di\Container;
use yy\di\ServiceLocator;

abstract class Application extends Object
{

	public function __construct($config)
	{
		// 保存当前对象
		Yy::$app = $this;

		// 注册DI容器
		Yy::$container = new Container();

		// 注册服务定位器
		Yy::$components = new ServiceLocator($config['components']);

		// 注册错误处理函数
		$this->registerErrorHandler($config);
	}

	public function run()
	{
		echo 'run';
		$request = $this->getRequest();
		$response = $this->handleRequest($request);
		// $response->send();
	}

	abstract public function getRequest();

	abstract public function handleRequest($request);

	protected function registerErrorHandler($config)
	{

	}
}