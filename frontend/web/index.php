<?php
require(__DIR__.'/../../vendor/autoload.php');
$config = require(__DIR__ . '/../config/index.php');

Yy::createApplication('yy\web\Application', $config)->run();

Yy::$container = new yy\di\Container();
Yy::$serviceLocator = new yy\di\ServiceLocator($config['components']);
