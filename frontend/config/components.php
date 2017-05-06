<?php
return [
	'db' => [
		'class' => 'yii\db\Connection',
		'dsn' => 'mysql:host=localhost;dbname=yii2basic',
		'username' => 'root',
		'password' => '',
		'charset' => 'utf8',
	],
	'request' => [
		'class' => '\yy\web\Request',
	],
];