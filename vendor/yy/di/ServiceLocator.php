<?php

namespace yy\di;

class ServiceLocator extends Container
{

	public function __construct($components)
	{
		foreach ($components as $id => $component) {
			$this->setSingleton($id, $component);
		}
	}
}