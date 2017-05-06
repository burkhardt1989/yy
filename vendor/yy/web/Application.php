<?php
namespace yy\web;

use Yy;

class Application extends \yy\base\Application
{
	public function getRequest()
	{
		return Yy::$components->get('request');
	}

	public function handleRequest($request)
	{
		$response = null;
		return $response;
	}
}