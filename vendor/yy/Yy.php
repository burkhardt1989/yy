<?php

defined('YY_PATH') or define('YY_PATH', __DIR__);

require(__DIR__ . '/BaseYy.php');

class Yy extends yy\BaseYy
{

}

spl_autoload_register(['Yy', 'autoload'], true, true);
Yy::$classMap = require(__DIR__ . '/classes.php');
// Yy::$container = new yy\di\Container();