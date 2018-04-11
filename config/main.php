<?php

return array(
	//app
	'displayErrorDetails'    => false,
	'addContentLengthHeader' => false,

	//db
	'db' => array(
		'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'oye_moe',
        'username'  => 'root',
        'password'  => 'Hyswr0.0',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
	),

	//logger
	'logger' => array(
		'name' => 'girleo',
        'level' => Monolog\Logger::DEBUG,
        'path' => __DIR__ . '/../logs/app.log',
	)
);