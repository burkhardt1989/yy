<?php
namespace yy\base;

use Yy;

abstract class Application extends Object
{
	public function __construct($config)
	{
		Yy::$app = $this;
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
}