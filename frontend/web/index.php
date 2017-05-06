<?php
require(__DIR__.'/../../vendor/autoload.php');
$config = require(__DIR__ . '/../config/index.php');

Yy::createApplication('yy\web\Application', $config)->run();
